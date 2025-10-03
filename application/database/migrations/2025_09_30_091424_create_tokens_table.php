<?php

use App\Models\Account;
use App\Models\ApiService;
use App\Models\TokenType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TokenType::class)->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();;
            $table->string('value');
            $table->foreignIdFor(Account::class)->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();;
            $table->foreignIdFor(ApiService::class)->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();;

            $table->unique(['api_service_id', 'token_type_id', 'account_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
}
