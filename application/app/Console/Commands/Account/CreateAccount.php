<?php

namespace App\Console\Commands\Account;

use App\Models\Company;
use Illuminate\Console\Command;

class CreateAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:create {name} {company_id}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new account';

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
        $company = Company::find($this->argument('company_id'));

        if (!$company) {
            $this->error('Компания не найдена');

            // Показать список существующих компаний
            $companies = Company::all(['id', 'name']);
            if ($companies->isNotEmpty()) {
                $this->info("Доступные компании:");
                $this->table(
                    ['ID', 'Название'],
                    $companies->toArray()
                );
            }

            return 1;
        }

        $company->accounts()->create([
            'name' => $this->argument('name'),
        ]);

        $this->info("Аккаунт " . $this->argument('name') . " создан");

        return 0;
    }
}
