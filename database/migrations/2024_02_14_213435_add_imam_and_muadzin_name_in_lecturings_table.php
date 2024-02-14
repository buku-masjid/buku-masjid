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
        Schema::table('lecturings', function (Blueprint $table) {
            $table->string('imam_name', 60)->nullable()->comment('filled if friday lecturings');
            $table->string('muadzin_name', 60)->nullable()->comment('filled if friday lecturings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturings', function (Blueprint $table) {
            $table->dropColumn('imam_name');
            $table->dropColumn('muadzin_name');
        });
    }
};
