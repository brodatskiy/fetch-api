<?php

namespace App\Services;


use App\Jobs\SaveData;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

class FetchService
{
    private int $page = 1;
    const LIMIT = 500;
    private int $backoffDelay = 60;
    private int $backoffAttempt = 0;
    private int $backoffMaxAttempt = 5;
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Config::get('services.fetch_api.host'),
            'timeout' => Config::get('services.fetch_api.timeout', 2.0),
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function fetch(string $modelName, string $uri, string $dateFrom, string $dateTo): void
    {
        do {
            $response = $this->client->request('GET', $uri, [
                'query' => [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'key' => Config::get('services.fetch_api.key'),
                    'page' => $this->page,
                    'limit' => self::LIMIT,
                ],
            ]);

            $hasMorePages = $data['meta']['last_page'] > $this->page ?? false;
            [$remainingRequests] = $response->getHeader('X-RateLimit-Remaining');

            if ($remainingRequests == 0) {
                $this->waitForBackoff();
                continue;
            }

            $data = json_decode($response->getBody()->getContents(), true);
            SaveData::dispatch($modelName, $data);
            $this->page++;
            $this->backoffAttempt = 0;
        } while ($hasMorePages);

    }

    private function waitForBackoff(): void
    {
        $delay = $this->backoffDelay * pow(2, $this->backoffAttempt);
        $this->backoffAttempt++;
        sleep($delay);
    }
}