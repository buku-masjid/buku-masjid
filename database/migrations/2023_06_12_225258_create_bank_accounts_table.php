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

        if (!Schema::hasColumn('books', 'bank_account_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->unsignedInteger('bank_account_id')->nullable()->after('status_id');

                $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict');
            });
        } else {
            Schema::table('books', function (Blueprint $table) {
                $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict');
            });
        }

        if (!Schema::hasColumn('transactions', 'bank_account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unsignedInteger('bank_account_id')->nullable()->after('category_id');

                $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict');
            });
        } else {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('books', 'bank_account_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropForeign('books_bank_account_id_foreign');
                $table->dropColumn('bank_account_id');
            });
        }
        if (Schema::hasColumn('transactions', 'bank_account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign('transactions_bank_account_id_foreign');
                $table->dropColumn('bank_account_id');
            });
        }

        Schema::dropIfExists('bank_accounts');
    }
};
