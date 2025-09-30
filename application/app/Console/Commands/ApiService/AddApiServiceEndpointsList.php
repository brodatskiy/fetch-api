<?php

namespace App\Console\Commands\ApiService;

use App\Models\ApiService;
use Illuminate\Console\Command;

class AddApiServiceEndpointsList extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-service:list';
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
        $apiServices = ApiService::all(['id', 'name', 'endpoints', 'supported_token_types']);
        if ($apiServices->isNotEmpty()) {
            $this->info("Доступные сервисы:");
            $this->table(
                ['ID', 'Название', 'endpoints', 'supported_token_types'],
                $apiServices->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'endpoints' => $service->endpoints ? implode(', ', $service->endpoints) : 'нет',
                        'supported_token_types' => $service->supported_token_types ? implode(', ', $service->supported_token_types) : 'нет',
                    ];
                })->toArray()
            );
        }


        return 0;
    }
}
