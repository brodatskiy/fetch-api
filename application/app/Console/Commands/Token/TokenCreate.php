<?php

namespace App\Console\Commands\Token;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Token;
use App\Models\TokenType;
use Illuminate\Console\Command;

class TokenCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:create {tokenTypeId?} {tokenValue?} {accountId?} {apiServiceId?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $tokenTypeId = $this->argument('tokenTypeId');

        if (!$tokenTypeId) {
            $tokenTypes = TokenType::all(['name', "id"])->pluck('name', 'id')->toArray();
            $tokenTypeName = $this->choice('Выберете тип токена', $tokenTypes);
            $tokenTypeId = array_search($tokenTypeName, $tokenTypes);
        }

        $tokenValue = $this->argument('tokenValue') ?? $this->ask('Значение токена');
        $accountId = $this->argument('accountId');

        if (!$accountId) {
            $accounts = Account::all(['name', "id"])->pluck('name', 'id')->toArray();
            $accountName = $this->choice('Выберете аккаунт', $accounts);
            $accountId = array_search($accountName, $accounts);
        }

        $apiServiceId = $this->argument('apiServiceId');

        if (!$apiServiceId) {
            $apiServices = ApiService::all(['name', "id"])->pluck('name', 'id')->toArray();
            $apiServiceName = $this->choice('Выберете сервис', $apiServices);
            $apiServiceId = array_search($apiServiceName, $apiServices);
        }

        $token = Token::create([
            'token_type_id' => $tokenTypeId,
            'value' => $tokenValue,
            'account_id' => $accountId,
            'api_service_id' => $apiServiceId,
        ]);

        $this->info("Токен создан");

        return 0;
    }
}
