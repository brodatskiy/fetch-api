<?php

namespace App\Console\Commands\FetchData;

use Illuminate\Console\Command;

class FetchAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:all {account_id} {apiService} {dateFrom?} {dateTo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all data from remote server';

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
        $arguments = [
            'accountId' => $this->argument('account_id'),
            'apiService' => $this->argument('apiService'),
            'dateFrom' => $this->argument('dateFrom'),
            'dateTo' => $this->argument('dateTo'),
        ];

        $this->call('fetch:incomes', $arguments);
        $this->call('fetch:orders', $arguments);
        $this->call('fetch:sales', $arguments);
        $this->call('fetch:stocks', $arguments);

        return 0;
    }
}
