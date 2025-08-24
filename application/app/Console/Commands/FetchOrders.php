<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\FetchService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class FetchOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:orders {dateFrom?} {dateTo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Orders';

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
     * @throws GuzzleException
     */
    public function handle(FetchService $fetchService)
    {
        $dateFrom = $this->argument('dateFrom') ? $this->argument('dateFrom') : '1970-01-01';
        $dateTo = $this->argument('dateTo') ? $this->argument('dateTo') : Carbon::today()->format('Y-m-d');

        $orders =  $fetchService->fetch('/api/orders', $dateFrom, $dateTo);

        foreach ($orders as $order) {
            Order::create($order);
        }

        $this->info('Данные загружены');
        return 0;
    }
}
