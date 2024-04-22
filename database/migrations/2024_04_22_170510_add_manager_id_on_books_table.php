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
        if (!Schema::hasColumn('books', 'manager_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->unsignedInteger('manager_id')->nullable()->after('creator_id');
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('books', 'manager_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropForeign('books_manager_id_foreign');
                $table->dropColumn('manager_id');
            });
        }
    }
};
