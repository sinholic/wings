<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id');
            $table->string('product_code', 18);
            $table->string('product_name', 30);
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->string('unit', 5);
            $table->decimal('sub_total', 10, 2);
            $table->string('currency', 5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
}

