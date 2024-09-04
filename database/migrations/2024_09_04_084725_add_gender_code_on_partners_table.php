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
        if (!Schema::hasColumn('partners', 'gender_code')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->string('gender_code', 10)->nullable()->after('level_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('partners', 'gender_code')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('gender_code');
            });
        }
    }
};
