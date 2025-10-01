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
    protected $signature = 'fetch:all {account_id}';

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

        $this->call('fetch:incomes', [
            'account_id' => $this->argument('account_id'),
        ]);
        $this->call('fetch:orders', [
            'account_id' => $this->argument('account_id'),
        ]);
        $this->call('fetch:sales', [
            'account_id' => $this->argument('account_id'),
        ]);
        $this->call('fetch:stocks', [
            'account_id' => $this->argument('account_id'),
        ]);

        return 0;
    }
}
