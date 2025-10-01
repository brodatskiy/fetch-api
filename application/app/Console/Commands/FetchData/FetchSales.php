<?php

namespace App\Console\Commands\FetchData;

use App\Models\Sale;
use App\Services\FetchService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class FetchSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:sales {account_id} {dateFrom?} {dateTo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Sales';

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

        $dateFrom = $this->argument('dateFrom') ? $this->argument('dateFrom') : '1970-01-01';
        $dateTo = $this->argument('dateTo') ? $this->argument('dateTo') : Carbon::today()->format('Y-m-d');

        $fetchService->fetch(Sale::class, '/api/sales', $dateFrom, $dateTo);

        return 0;
    }
}
