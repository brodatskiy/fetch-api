<?php

namespace App\Console\Commands\FetchData;

use App\Models\Account;
use App\Models\ApiService;
use App\Services\FetchService;
use App\Services\FetchServiceFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FetchIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:incomes {accountId} {apiService} {dateFrom?} {dateTo?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Incomes';

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
        $dateFrom = $this->argument('dateFrom') ? $this->argument('dateFrom') : '1970-01-01';
        $dateTo = $this->argument('dateTo') ? $this->argument('dateTo') : Carbon::today()->format('Y-m-d');
        $account = Account::find($this->argument('accountId'));
        $apiService = ApiService::find($this->argument('apiService'));
        $endpoint = $apiService->getEndpointByName('Incomes');

        $fetchService = FetchServiceFactory::make($account, $apiService);

        $fetchService->fetch($endpoint, $dateFrom, $dateTo);

        $this->info("Загрузка доходов окончена");

        return 0;
    }
}
