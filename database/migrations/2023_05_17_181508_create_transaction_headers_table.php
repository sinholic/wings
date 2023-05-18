<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionHeadersTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_headers', function (Blueprint $table) {
            $table->id();
            $table->string('document_code', 3);
            $table->string('document_number', 10);
            $table->string('username', 50);
            $table->decimal('total', 10, 2);
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_headers');
    }
}