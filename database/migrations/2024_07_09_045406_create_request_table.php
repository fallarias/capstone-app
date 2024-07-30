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
        Schema::create('tbl_request', function (Blueprint $table) {
            $table->id('request_id');
            $table->string('client_lname');
            $table->string('client_fname');
            $table->string('client_mname');
            $table->string('Office_use');
            $table->string('Request_type');
            $table->string('Reason_of_request');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('client_id')->on('tbl_client');

            $table->unsignedBigInteger('transaction_id');     
            $table->foreign('transaction_id')->references('transaction_id')->on('tbl_transaction');

            $table->unsignedBigInteger('supplier_id');      
            $table->foreign('supplier_id')->references('supplier_id')->on('tbl_supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_request');
    }
};
