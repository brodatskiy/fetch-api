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

    /**
     * @throws GuzzleException
     */
    public function fetch(string $modelName, string $uri, string $dateFrom, string $dateTo): void
    {
        $client = new Client([
            'base_uri' => Config::get('services.fetch_api.host'),
            'timeout' => Config::get('services.fetch_api.timeout', 2.0),
        ]);

        do {
            $response = $client->request('GET', $uri, [
                'query' => [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'key' => Config::get('services.fetch_api.key'),
                    'page' => $this->page,
                    'limit' => self::LIMIT,
                ],
            ]);

            $remainingRequests = $response->getHeader('X-RateLimit-Remaining');

            if ($remainingRequests[0] == 0) {
                sleep(60);
            }

            $data = json_decode($response->getBody()->getContents(), true);

            SaveData::dispatch($modelName, $data);

            $hasMorePages = $data['meta']['last_page'] > $this->page ?? false;
            $this->page++;
        } while ($hasMorePages);

    }
}