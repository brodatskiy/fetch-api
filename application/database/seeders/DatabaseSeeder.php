<?php

namespace Database\Seeders;

use App\Models\ApiService;
use App\Models\Company;
use App\Models\TokenType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
            'name' => 'test Company',
        ]);
        $company->accounts()->create([
            'name' => 'test Account',
        ]);

        $tokenTypes = ['api-key', 'bearer', 'basic'];

        foreach ($tokenTypes as $tokenType) {
            TokenType::create(['name' => $tokenType]);
        }

        $apiService = ApiService::create([
            'name' => 'test Api',
            'host' => 'test.test.test',
        ]);

        $apiService->tokenTypes()->attach([1, 2, 3]);
    }
}
