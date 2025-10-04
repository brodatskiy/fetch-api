<?php

namespace App\Console\Commands\Endpoint;

use App\Enum\Model;
use App\Models\ApiService;
use App\Models\Endpoint;
use Illuminate\Console\Command;

class EndpointAdd extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'endpoint:add 
                            {name?}
                            {urn?}
                            {model?}
                            {apiServiceId?}';
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
        $name = $this->argument('name') ?? $this->ask('Имя "endpoint"');
        $urn = $this->argument('urn') ?? $this->ask('urn');
        $model = $this->argument('model') ?? $this->choice('Выберете тип токена', Model::values());
        $apiServiceId = $this->argument('apiServiceId');

        if (!$apiServiceId) {
            $apiServices = ApiService::all(['name', "id"])->pluck('name', 'id')->toArray();
            $apiServiceName = $this->choice('Выберете сервис', $apiServices);
            $apiServiceId = array_search($apiServiceName, $apiServices);
        }

        $apiService = ApiService::find($apiServiceId);

        $apiService->endpoints()->create([
            'name' => $name,
            'urn' => $urn,
            'model' => $model,
        ]);

        $this->info("Endpoint " . $name . " создан");

        return 0;
    }
}
