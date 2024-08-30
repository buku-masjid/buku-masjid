<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date');
            $table->unsignedDecimal('amount', 12);
            $table->boolean('in_out')->unsigned();
            $table->string('description');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('partner_id')->nullable();
            $table->unsignedInteger('bank_account_id')->nullable();
            $table->unsignedInteger('book_id')->default(config('masjid.default_book_id'));
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
