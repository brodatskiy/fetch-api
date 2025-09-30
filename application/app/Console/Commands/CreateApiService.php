<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use Illuminate\Console\Command;

class CreateApiService extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-service:create 
                            {name}
                            {host}
                            {--T|token_types=*}
                            {--E|endpoints=*}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Token Type';

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
        ApiService::create([
            'name' => $this->argument('name'),
            'host' => $this->argument('host'),
            'endpoints' => $this->option('endpoints'),
            'supported_token_types' => $this->option('token_types'),
        ]);

        $this->info("Api сервис " . $this->argument('name') . " создан");

        return 0;
    }
}
