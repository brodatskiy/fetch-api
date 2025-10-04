<?php

namespace App\Console\Commands\FetchData;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Stock;
use App\Services\FetchService;
use App\Services\FetchServiceFactory;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class FetchStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:stocks {accountId} {apiService} {dateFrom?} {dateTo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Stocks';

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
        $dateFrom = $this->argument('dateFrom') ? $this->argument('dateFrom') : Carbon::today()->format('Y-m-d');
        $dateTo = $this->argument('dateTo') ? $this->argument('dateTo') : Carbon::today()->format('Y-m-d');
        $account = Account::find($this->argument('accountId'));
        $apiService = ApiService::find($this->argument('apiService'));
        $endpoint = $apiService->getEndpointByName('Stocks');

        $fetchService = FetchServiceFactory::make($account, $apiService);

        $fetchService->fetch($endpoint, $dateFrom, $dateTo);

        $this->info("Загрузка акций окончена");

        return 0;
    }
}
