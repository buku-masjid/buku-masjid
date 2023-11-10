<?php

use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('report_periode_code', 20)->default(Book::REPORT_PERIODE_IN_MONTHS)->after('bank_account_id');
            $table->string('start_week_day_code', 10)->default('monday')->after('report_periode_code');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('report_periode_code');
            $table->dropColumn('start_week_day_code');
        });
    }
};
