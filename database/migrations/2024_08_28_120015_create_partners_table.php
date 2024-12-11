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
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('type_code');
            $table->string('level_code')->nullable();
            $table->string('gender_code', 10)->nullable();
            $table->string('phone')->nullable();
            $table->string('pob', 60)->nullable();
            $table->date('dob')->nullable();
            $table->unsignedTinyInteger('work_type_id')->nullable();
            $table->string('work')->nullable();
            $table->string('address')->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('marital_status_id')->nullable();
            $table->unsignedTinyInteger('financial_status_id')->nullable();
            $table->unsignedTinyInteger('activity_status_id')->nullable();
            $table->unsignedTinyInteger('religion_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });

        if (!Schema::hasColumn('transactions', 'partner_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unsignedInteger('partner_id')->nullable()->after('bank_account_id');

                $table->foreign('partner_id')->references('id')->on('partners')->onDelete('restrict');
            });
        } else {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('partner_id')->references('id')->on('partners')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'partner_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign('transactions_partner_id_foreign');
                $table->dropColumn('partner_id');
            });
        }

        Schema::dropIfExists('partners');
    }
};
