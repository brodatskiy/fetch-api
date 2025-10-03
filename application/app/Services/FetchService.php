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

    public function __construct(Account $account, ApiService $apiService)
    {
        $this->account = $account;
        $this->client = ApiClientFactory::make($account, $apiService);
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

            SaveData::dispatch($this->account->id, $endpoint->model->fullClass(), $data);
            $this->page++;
        } while ($hasMorePages);
    }

    protected function logRequest(Endpoint $endpoint, string $dateFrom, string $dateTo, GuzzleException $e): void
    {
        $statusCode = $e->getCode();
        $statusMessage = $e->getMessage();
        $message = sprintf(
            " \nDateFrom: %s, \nDateTo: %s, \nPage: %d, \nStatus code: %d, \nStatus message: \n%s",
//            $modelName,
//            $uri,
            $dateFrom,
            $dateTo,
            $this->page,
            $statusCode,
            $statusMessage
        );

        if ($statusCode >= 400) {
            Log::error($message);
        }
    }
}