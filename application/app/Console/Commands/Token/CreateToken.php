<?php

namespace App\Console\Commands\Token;

use App\Models\Token;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:create {token_type_id} {token_value} {account_id} {api_service_id}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new token';

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
        $token = Token::create([
            'token_type_id' => $this->argument('token_type_id'),
            'token_value' => $this->argument('token_value'),
            'account_id' => $this->argument('account_id'),
            'api_service_id' => $this->argument('api_service_id'),
        ]);

        return 0;
    }
}
