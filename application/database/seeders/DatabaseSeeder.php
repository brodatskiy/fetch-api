<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Company;
use App\Models\Endpoint;
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
            'host' => 'http://109.73.206.144:6969',
        ]);

        $endpoints = [
            [
                'name' => "Incomes",
                'urn' => '/api/incomes',
                'model' => 'Income'
            ],            [
                'name' => "Stocks",
                'urn' => '/api/stocks',
                'model' => 'Stock'
            ],            [
                'name' => "Orders",
                'urn' => '/api/orders',
                'model' => 'Order'
            ],            [
                'name' => "Sales",
                'urn' => '/api/sales',
                'model' => 'Sale'
            ],
        ];

        foreach ($endpoints as $endpoint) {
            $apiService->endpoints()->create($endpoint);
        }

        $apiService->tokenTypes()->attach([1, 2, 3]);
    }
}
