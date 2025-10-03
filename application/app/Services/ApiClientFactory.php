<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ApiService;

class ApiClientFactory
{
    public static function make(Account $account, ApiService $apiService): ApiClient
    {
        return new ApiClient($account, $apiService);
    }
}