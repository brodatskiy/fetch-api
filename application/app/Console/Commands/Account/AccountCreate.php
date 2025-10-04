<?php

namespace App\Console\Commands\Account;

use App\Models\Company;
use Illuminate\Console\Command;

class AccountCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:create {name?} {companyId?}';
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
        $name = $this->argument('name') ?? $this->ask('Имя');
        $company_id = $this->argument('companyId');

        if (!$company_id) {
            $companies = Company::all(['name', "id"])->pluck('name', 'id')->toArray();
            $company_name = $this->argument('companyId') ?? $this->choice('Выберете компанию', $companies);
            $company_id = array_search($company_name, $companies);
        }

        $company = Company::find($company_id);

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
            'name' => $name,
        ]);

        $this->info("Аккаунт " . $name. " для компании " . $company->name . " создан");

        return 0;
    }
}
