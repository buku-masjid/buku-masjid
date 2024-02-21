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
        if (!Schema::hasColumn('lecturings', 'imam_name')) {
            Schema::table('lecturings', function (Blueprint $table) {
                $table->string('imam_name', 60)->nullable()->after('lecturer_name');
            });
        }
        if (!Schema::hasColumn('lecturings', 'muadzin_name')) {
            Schema::table('lecturings', function (Blueprint $table) {
                $table->string('muadzin_name', 60)->nullable()->after('lecturer_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('lecturings', 'imam_name')) {
            Schema::table('lecturings', function (Blueprint $table) {
                $table->dropColumn('imam_name');
            });
        }
        if (Schema::hasColumn('lecturings', 'muadzin_name')) {
            Schema::table('lecturings', function (Blueprint $table) {
                $table->dropColumn('muadzin_name');
            });
        }
    }
};
