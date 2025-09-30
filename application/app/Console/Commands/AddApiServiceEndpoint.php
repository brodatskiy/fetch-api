<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use Illuminate\Console\Command;

class AddApiServiceEndpoint extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-service:add 
                            {id}
                            {--E|endpoints=*}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add api service endpoint';

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
        $apiService = ApiService::find($this->argument('id'));
        $apiService->addEndpoints( $this->option('endpoints'));

        return 0;
    }
}
