<?php

namespace App\Console\Commands\ApiService;

use App\Models\ApiService;
use App\Models\TokenType;
use Illuminate\Console\Command;

class CreateApiService extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-service:create 
                            {name?}
                            {host?}
                            {--T|token_type_ids=*}';
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
        $name = $this->argument('name') ?? $this->ask('Имя сервиса');
        $host = $this->argument('host') ?? $this->ask('хост');
        $token_types_ids = $this->option('token_type_ids');

        if (!$token_types_ids) {
            $token_types = TokenType::all(['name', "id"])->pluck('name', 'id')->toArray();
            $token_types_choice = $this->choice('Выберете тип токена', $token_types, null, null,  true);
            $token_types_ids = array_keys(array_intersect($token_types, $token_types_choice));
        }

        $apiService = ApiService::create([
            'name' => $name,
            'host' => $host,
        ]);

        $apiService->tokenTypes()->attach($token_types_ids);

        $this->info("Api сервис " . $this->argument('name') . " создан");

        return 0;
    }
}
