<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Endpoint;
use App\Models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{
    public Client $instance;
    private Account $account;

    private ?string $currentAuthStrategy = null;
    private ?Token $token;

    public function __construct(Account $account, ApiService $apiService)
    {
        $this->account = $account;
        $this->instance = new Client([
            'base_uri' => $apiService->host,
            'timeout' => Config::get('services.fetch_api.timeout', 2.0),
        ]);

    }

    /**
     * @throws GuzzleException
     */
    public function makeRequestWithAuth(Endpoint $endpoint, string $dateFrom, string $dateTo, int $page, int $limit): ResponseInterface
    {
        $query = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'limit' => $limit,
        ];

        if (!$this->currentAuthStrategy) {
            $this->selectAuthStrategy($endpoint, $query);
        }

        return match ($this->currentAuthStrategy) {
            'api-key' => $this->instance->request('GET', $endpoint->urn, [
                'query' => [...$query, 'key' => $this->token->value],
            ]),
            'bearer' => $this->instance->request('GET', $endpoint->urn, [
                'auth' => ['Bearer', $this->token->value],
                'query' => $query,
            ]),
            'basic' => $this->instance->request('GET', $endpoint->urn, [
                'auth' => ['Basic', $this->token->value],
                'query' => $query,
            ]),
        };

    }

    /**
     * @throws GuzzleException
     */
    private function selectAuthStrategy($endpoint, $query): void
    {
        $tokens = $this->account->tokens()->get();

        foreach ($tokens as $token) {
            $tokenName = $token->getTokenTypeName();

            switch ($tokenName) {
                case 'api-key':
                {
                    $query['key'] = $token->value;
                    $response = $this->instance->request('GET', $endpoint->urn, [
                        'query' => $query,
                    ]);
                    break;
                }
                case 'bearer':
                {
                    $response = $this->instance->request('GET', $endpoint->urn, [
                        'auth' => ['Bearer', $token->value],
                        'query' => $query,
                    ]);
                    break;
                }
                case 'basic':
                {
                    $response = $this->instance->request('GET', $endpoint->urn, [
                        'auth' => ['Basic', $token->value],
                        'query' => $query,
                    ]);
                    break;
                }
                default:
                    $response = null;
            }

            if ($response) {
                $this->currentAuthStrategy = $tokenName;
                $this->token = $token;
            }
        }

    }
}