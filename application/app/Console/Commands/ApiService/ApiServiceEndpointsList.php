<?php

namespace App\Console\Commands\ApiService;

use App\Models\ApiService;
use Illuminate\Console\Command;

class ApiServiceEndpointsList extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-service:endpoints {apiServiceId?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Api Service Endpoints';

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
        $apiServiceId = $this->argument('apiServiceId');

        if (!$apiServiceId) {
            $apiServices = ApiService::all(['name', "id"])->pluck('name', 'id')->toArray();
            $apiServiceName = $this->choice('Выберете сервис', $apiServices);
            $apiServiceId = array_search($apiServiceName, $apiServices);
        }

        $apiService = ApiService::find($apiServiceId);

        $endpoints = $apiService->endpoints()->get();

        if ($endpoints->isNotEmpty()) {
            $this->info("Доступные точки входа:");
            $this->table(
                ['ID', 'Название', 'URN', 'Связанная модель'],
                $endpoints->map(function ($endpoint) {
                    return [
                        'id' => $endpoint->id,
                        'name' => $endpoint->name,
                        'urn' => $endpoint->urn,
                        'model' => $endpoint->model->fullclass(),
                    ];
                })->toArray()
            );
        }


        return 0;
    }
}
