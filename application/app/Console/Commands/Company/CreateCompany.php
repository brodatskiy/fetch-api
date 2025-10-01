<?php

namespace App\Console\Commands\Company;

use App\Models\Company;
use Illuminate\Console\Command;

class CreateCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:create {name}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new company';

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
        Company::create([
            'name' => $this->argument('name'),
        ]);

        $this->info("Компания " . $this->argument('name') . " создана");

        return 0;
    }
}
