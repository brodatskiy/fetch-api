<?php

namespace App\Services;


use App\Jobs\SaveData;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class FetchService
{
    private int $page = 1;
    const LIMIT = 500;
    private int $backoffDelay = 60;
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Config::get('services.fetch_api.host'),
            'timeout' => Config::get('services.fetch_api.timeout', 2.0),
        ]);
    }

    public function fetch(string $modelName, string $uri, string $dateFrom, string $dateTo): void
    {
        do {
            $response = null;
            try {
                $response = $this->client->request('GET', $uri, [
                    'query' => [
                        'dateFrom' => $dateFrom,
                        'dateTo' => $dateTo,
                        'key' => Config::get('services.fetch_api.key'),
                        'page' => $this->page,
                        'limit' => self::LIMIT,
                    ],
                ]);

            } catch (GuzzleException $e) {
                $this->logRequest($modelName, $uri, $dateFrom, $dateTo, $e);
                break;
            }

            $data = json_decode($response->getBody()->getContents(), true);
            $hasMorePages = $data['meta']['last_page'] > $this->page ?? false;

            [$remainingRequests] = $response->getHeader('X-RateLimit-Remaining');

            if ($remainingRequests == 0) {
                sleep($this->backoffDelay);
                continue;
            }

            SaveData::dispatch($modelName, $data);
            $this->page++;
        } while ($hasMorePages);
    }

    protected function logRequest(string $modelName, string $uri, string $dateFrom, string $dateTo, GuzzleException $e): void
    {
        $statusCode = $e->getCode();
        $statusMessage = $e->getMessage();
        $message = sprintf(
            "\nModel name: %s, \nURI: %s, \nDateFrom: %s, \nDateTo: %s, \nPage: %d, \nStatus code: %d, \nStatus message: \n%s",
            $modelName,
            $uri,
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