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
        Schema::create('tbl_transaction', function (Blueprint $table) {
            $table->id('transaction_id');
        
            $table->unsignedBigInteger('client_id');
        
            $table->foreign('client_id')->references('client_id')->on('tbl_client');

            $table->unsignedBigInteger('staff_id');
        
            $table->foreign('staff_id')->references('staff_id')->on('tbl_staff');
            $table->string('status');
            
            // $table->foreignId('request_id')->constrained(
            //     table: 'tbl_request', indexName: 'requests_id'
            // );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
