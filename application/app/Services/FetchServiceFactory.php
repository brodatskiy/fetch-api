<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ApiService;

class FetchServiceFactory
{
    public static function make(Account $account, ApiService $apiService): FetchService
    {
        return new FetchService($account, $apiService);
    }
}