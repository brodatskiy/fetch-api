<?php

namespace App\Services;

use App\Jobs\SaveData;
use App\Models\Account;
use App\Models\ApiService;
use App\Models\Endpoint;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class FetchService
{
    private int $page = 1;
    private const LIMIT = 200;
    private const BACKOFF_DELAY = 60;
    private const MAX_RETRIES = 3;

    private ApiClient $client;
    private Account $account;
    private array $context;
    private int $retryCount = 0;

    public function __construct(Account $account, ApiService $apiService)
    {
        $this->account = $account;
        $this->client = ApiClientFactory::make($account, $apiService);
        $this->context = $this->buildBaseContext();
    }

    public function fetch(Endpoint $endpoint, string $dateFrom, string $dateTo): void
    {
        do {
            $response = $this->makeRequestWithRetry($endpoint, $dateFrom, $dateTo);

            if (!$response) {
                break;
            }

            $data = $this->parseResponse($response);
            $hasMorePages = $this->hasMorePages($data);

            $this->processData($endpoint, $data['data']);
            $this->page++;
            $this->retryCount = 0;

        } while ($hasMorePages);

        $this->logContextComplete($endpoint);
    }

    private function buildBaseContext(): array
    {
        return [
            'account' => $this->account->name,
            'pagination' => [
                'page' => $this->page,
                'limit' => self::LIMIT,
            ],
        ];
    }

    private function makeRequestWithRetry(Endpoint $endpoint, string $dateFrom, string $dateTo): ?ResponseInterface
    {
        try {
            return $this->client->makeRequestWithAuth($endpoint, $dateFrom, $dateTo, $this->page, self::LIMIT);
        } catch (GuzzleException $e) {
            $this->logRequestError($endpoint, $dateFrom, $dateTo, $e);

            if ($this->shouldRetry($e)) {
                return $this->retryRequest($endpoint, $dateFrom, $dateTo);
            }

            return null;
        }
    }

    private function shouldRetry(GuzzleException $e): bool
    {
        $statusCode = $e->getCode();

        return $statusCode >= 500 || $statusCode === 429;
    }

    private function retryRequest(Endpoint $endpoint, string $dateFrom, string $dateTo): ?ResponseInterface
    {
        if ($this->retryCount >= self::MAX_RETRIES) {
            Log::warning('Превышено количество попыток', $this->context);
            return null;
        }

        $this->retryCount++;
        sleep(self::BACKOFF_DELAY * $this->retryCount);

        Log::info("Попытка № $this->retryCount", $this->context);

        return $this->makeRequestWithRetry($endpoint, $dateFrom, $dateTo);
    }


    private function parseResponse(ResponseInterface $response): array
    {
        $contents = $response->getBody()->getContents();
        return json_decode($contents, true);
    }

    private function hasMorePages(array $data): bool
    {
        return $data['meta']['last_page'] > $this->page ?? false;
    }

    private function handleRateLimit(): void
    {
        Log::warning('Rate limit hit, waiting...', $this->context);
        sleep(self::BACKOFF_DELAY);
    }

    private function processData(Endpoint $endpoint, array $data): void
    {
        if (!empty($data)) {
            SaveData::dispatch($this->account->id, $endpoint->model, $data);
        }
    }

    private function logRequestError(Endpoint $endpoint, string $dateFrom, string $dateTo, GuzzleException $e): void
    {
        $logContext = array_merge($this->context, [
            'api_service' => $endpoint->apiService->name,
            'model' => $endpoint->model->fullClass(),
            'endpoint' => $endpoint->urn,
            'error' => $e->getMessage(),
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'retry_count' => $this->retryCount,
        ]);

        $statusCode = $e->getCode();

        if ($statusCode >= 500) {
            Log::error("Bad request $statusCode", $logContext);
        } elseif ($statusCode === 429) {
            Log::warning('Too many requests', $logContext);
        } else {
            Log::error("Ошибка: {$e->getMessage()}", $logContext);
        }
    }

    private function logContextComplete(Endpoint $endpoint): void
    {
        $context = array_merge($this->context, [
            'endpoint' => $endpoint->urn,
            'page' => $this->page,
            'retry_count' => $this->retryCount,
        ]);

        Log::info('Fetch process completed', $context);
    }
}