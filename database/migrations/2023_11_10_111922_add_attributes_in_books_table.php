<?php

use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('books', 'budget')) {
            Schema::table('books', function (Blueprint $table) {
                $table->unsignedDecimal('budget', 12)->nullable()->after('bank_account_id');
            });
        }
        if (!Schema::hasColumn('books', 'report_periode_code')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('report_periode_code', 20)->default(Book::REPORT_PERIODE_IN_MONTHS)->after('budget');
            });
        }
        if (!Schema::hasColumn('books', 'start_week_day_code')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('start_week_day_code', 10)->default('monday')->after('report_periode_code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('books', 'budget')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('budget');
            });
        }
        if (Schema::hasColumn('books', 'report_periode_code')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('report_periode_code');
            });
        }
        if (Schema::hasColumn('books', 'start_week_day_code')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('start_week_day_code');
            });
        }
    }
};
