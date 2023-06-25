<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_account_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bank_account_id');
            $table->date('date');
            $table->unsignedDecimal('amount', 12);
            $table->string('description')->nullable();
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account_balances');
    }
};
