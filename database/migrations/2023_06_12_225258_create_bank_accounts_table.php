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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('number', 30);
            $table->string('account_name', 60);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('bank_account_id')->nullable()->after('status_id');

            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('bank_account_id');
        });
    }
};
