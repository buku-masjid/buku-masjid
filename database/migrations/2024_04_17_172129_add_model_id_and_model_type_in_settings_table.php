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
        if (!Schema::hasColumn('settings', 'model_id')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('model_id', 60)->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('settings', 'model_type')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('model_type', 60)->nullable()->after('model_id');
            });
        }
        if (Schema::hasColumn('settings', 'user_id')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropForeign('settings_user_id_foreign');
                $table->dropIndex('settings_key_user_id_index');
                $table->dropColumn('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('settings', 'model_id')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('model_id');
            });
        }
        if (Schema::hasColumn('settings', 'model_type')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('model_type');
            });
        }
    }
};
