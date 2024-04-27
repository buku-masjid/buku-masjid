<?php

use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('description')->nullable();
            $table->text('report_titles')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->unsignedInteger('manager_id')->nullable();
            $table->string('report_visibility_code', 10)->default(Book::REPORT_VISIBILITY_INTERNAL);
            $table->unsignedTinyInteger('status_id')->default(Book::STATUS_ACTIVE);
            $table->unsignedInteger('bank_account_id')->nullable();
            $table->unsignedDecimal('budget', 12)->nullable();
            $table->string('report_periode_code', 20)->default(Book::REPORT_PERIODE_IN_MONTHS);
            $table->string('start_week_day_code', 10)->default('monday');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
