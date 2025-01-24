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
        if (!Schema::hasColumn('users', 'access_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('access_token')->nullable()->after('role_id');
            });
        }
        if (Schema::hasColumn('users', 'api_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('api_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'access_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('access_token');
            });
        }
        if (!Schema::hasColumn('users', 'api_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('api_token')->nullable()->after('role_id');
            });
        }
    }
};
