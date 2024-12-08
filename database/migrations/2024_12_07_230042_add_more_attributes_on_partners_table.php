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
        if (!Schema::hasColumn('partners', 'pob')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->string('pob', 60)->nullable()->after('phone');
            });
        }
        if (!Schema::hasColumn('partners', 'dob')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->date('dob')->nullable()->after('pob');
            });
        }
        if (!Schema::hasColumn('partners', 'work_type_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->unsignedTinyInteger('work_type_id')->nullable()->after('dob');
            });
        }
        if (!Schema::hasColumn('partners', 'rt')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->string('rt', 3)->nullable()->after('address');
            });
        }
        if (!Schema::hasColumn('partners', 'rw')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->string('rw', 3)->nullable()->after('rt');
            });
        }
        if (!Schema::hasColumn('partners', 'marital_status_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->unsignedTinyInteger('marital_status_id')->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('partners', 'financial_status_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->unsignedTinyInteger('financial_status_id')->nullable()->after('marital_status_id');
            });
        }
        if (!Schema::hasColumn('partners', 'activity_status_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->unsignedTinyInteger('activity_status_id')->nullable()->after('financial_status_id');
            });
        }
        if (!Schema::hasColumn('partners', 'religion_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->unsignedTinyInteger('religion_id')->nullable()->after('activity_status_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('partners', 'pob')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('pob');
            });
        }
        if (Schema::hasColumn('partners', 'dob')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('dob');
            });
        }
        if (Schema::hasColumn('partners', 'work_type_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('work_type_id');
            });
        }
        if (Schema::hasColumn('partners', 'rt')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('rt');
            });
        }
        if (Schema::hasColumn('partners', 'rw')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('rw');
            });
        }
        if (Schema::hasColumn('partners', 'marital_status_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('marital_status_id');
            });
        }
        if (Schema::hasColumn('partners', 'financial_status_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('financial_status_id');
            });
        }
        if (Schema::hasColumn('partners', 'activity_status_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('activity_status_id');
            });
        }
        if (Schema::hasColumn('partners', 'religion_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('religion_id');
            });
        }
    }
};
