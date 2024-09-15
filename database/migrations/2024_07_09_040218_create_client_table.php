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
         if (!Schema::hasTable('tbl_client')) {
         Schema::create('tbl_client', function (Blueprint $table) {
             $table->id('client_id');
             $table->unsignedBigInteger('user_id');
      
             $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
         });
     }
    if (!Schema::hasTable('tbl_transaction')) {
        Schema::create('tbl_transaction', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('client_id');
            $table->string('Type');
            $table->string('Total_Office_of_Request')->nullable()->default(5);
            $table->string('Office_Done')->nullable()->default(0);
            $table->string('status');
            $table->timestamps();

            // Add foreign key constraints if needed
            $table->foreign('client_id')->references('client_id')->on('tbl_client')->onDelete('cascade');
           
        });
    }
    
    if (!Schema::hasTable('tbl_document')) {
        Schema::create('tbl_document', function (Blueprint $table) {
            $table->id('document_id');
            $table->string('template');
            $table->string('qrcode');
            

            $table->unsignedBigInteger('staff_id');
        
            $table->foreign('staff_id')->references('staff_id')->on('tbl_staff')->onDelete('cascade');

            $table->unsignedBigInteger('client_id');
        
            $table->foreign('client_id')->references('client_id')->on('tbl_client')->onDelete('cascade');
            $table->timestamps();
        });

    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('tbl_client');
        
        Schema::dropIfExists('tbl_document');
        Schema::dropIfExists('tbl_transaction');
    }
};
