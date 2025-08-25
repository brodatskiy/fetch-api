<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Services\FetchService;
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
    protected $signature = 'fetch:stocks {dateFrom?} {dateTo?}';

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
     * @param FetchService $fetchService
     * @return int
     * @throws GuzzleException
     */
    public function handle(FetchService $fetchService): int
    {
        $dateFrom = $this->argument('dateFrom') ? $this->argument('dateFrom') : Carbon::today()->format('Y-m-d');
        $dateTo = $this->argument('dateTo') ? $this->argument('dateTo') : Carbon::today()->format('Y-m-d');

        $fetchService->fetch(Stock::class,'/api/stocks', $dateFrom, $dateTo);

        return 0;
    }
}
