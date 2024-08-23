<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('transactions', 'bank_account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unsignedInteger('bank_account_id')->nullable()->after('category_id');
                $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'bank_account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign('transactions_bank_account_id_foreign');
                $table->dropColumn('bank_account_id');
            });
        }
    }
};
