<?php

namespace App\Console\Commands;

use App\Models\TokenType;
use Illuminate\Console\Command;

class CreateTokenType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token-type:create {name}';
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
        TokenType::create([
            'name' => $this->argument('name'),
        ]);

        $this->info("Token type " . $this->argument('name') . " created");

        return 0;
    }
}
