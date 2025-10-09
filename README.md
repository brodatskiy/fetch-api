# Fetch-API

Laravel приложение для импорта/синхронизации данных из стороннего API.

## Полная настройка

### Запуск

```bash
docker network create cluster
composer run-script setup-environment # создает .env из .env.example
docker compose --env-file application/.env up
```

### Консольные команды для работы с базовыми сущностями
```bash
docker exec app php artisan company:create {name}
docker exec app php artisan account:create {name} {companyId}
docker exec app php artisan api-service:create {name} {host} {--T|tokenTypeIds=*}
docker exec app php artisan api-service:endpoints {apiServiceId}
docker exec app php artisan token-type:create {name}
docker exec app php artisan token:create {tokenTypeId} {tokenValue} {accountId} {apiServiceId}
```

### Получение и обработка данных

#### Получение данных из API
```bash
# Получение определенных данных
docker exec app php artisan fetch:[incomes|sales|stocks|orders] {accountId} {apiService} {dateFrom} {dateTo}

# Получение всех данных
docker exec app php artisan fetch:all {accountId} {apiService} {dateFrom} {dateTo}
```