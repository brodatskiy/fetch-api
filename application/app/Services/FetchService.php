<?php

namespace App\Services;


use App\Jobs\SaveData;
use App\Models\Account;
use App\Models\ApiService;
use App\Models\Endpoint;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class FetchService
{
    private int $page = 1;
    const LIMIT = 500;
    private int $backoffDelay = 60;
    private ApiClient $client;
    private Account $account;
    private array $context;

    public function __construct(Account $account, ApiService $apiService)
    {
        $this->account = $account;
        $this->client = ApiClientFactory::make($account, $apiService);
        $this->context = [
            'account' => $this->account->name,
            'pagination' => [
                'page' => $this->page,
                'limit' => self::LIMIT,
            ],
        ];
    }

    public function fetch(Endpoint $endpoint, string $dateFrom, string $dateTo): void
    {
        do {
            $response = null;
            try {
                $response = $this->client->makeRequestWithAuth($endpoint, $dateFrom, $dateTo, $this->page, self::LIMIT);
            } catch (GuzzleException $e) {
                $this->logRequest($endpoint, $dateFrom, $dateTo, $e);
                break;
            }

            $data = json_decode($response->getBody()->getContents(), true);
            $hasMorePages = $data['meta']['last_page'] > $this->page ?? false;

            [$remainingRequests] = $response->getHeader('X-RateLimit-Remaining');

            if ($remainingRequests == 0) {
                sleep($this->backoffDelay);
                continue;
            }

            SaveData::dispatchSync($this->account->id, $endpoint->model, $data['data']);
            $this->page++;
        } while ($hasMorePages);
        $this->context['endpoint'] = $endpoint;
        Log::info('Fetch process completed', $this->context);
    }

    protected function logRequest(Endpoint $endpoint, string $dateFrom, string $dateTo, GuzzleException $e): void
    {
        $this->context[] = [
            'pagination' => [
                'page' => $this->page,
                'limit' => self::LIMIT,
            ],
            'api_service' => $endpoint->apiService->name,
            'model' => $endpoint->model->fullClass(),
            'endpoint' => $endpoint->urn,
            'error' => $e->getMessage(),
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
        ];

        $statusCode = $e->getCode();

        if ($statusCode >= 500) {
            Log::error("API Error {$statusCode}", $this->context);
        } elseif ($statusCode === 429) {
            Log::warning('Rate limit hit', $this->context);
        } elseif ($statusCode >= 400) {
            Log::warning("Client Error {$statusCode}", $this->context);
        }
    }
}