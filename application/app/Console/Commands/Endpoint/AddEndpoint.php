<?php

namespace App\Console\Commands\Endpoint;

use App\Models\ApiService;
use App\Models\Endpoint;
use Illuminate\Console\Command;

class AddEndpoint extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'endpoint:add 
                            {name}
                            {urn}
                            {model}
                            {api_service_id}';
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

        $apiService->endpoints()->create([
            'name' => $this->argument('name'),
            'urn' => $this->argument('urn'),
            'model' => $this->argument('model'),
        ]);

        return 0;
    }
}
