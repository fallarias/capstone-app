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
        Schema::create('tbl_document', function (Blueprint $table) {
            $table->id('document_id');
            $table->string('template');
            $table->string('qrcode');
            

            $table->unsignedBigInteger('staff_id');
        
            $table->foreign('staff_id')->references('staff_id')->on('tbl_staff');

            $table->unsignedBigInteger('client_id');
        
            $table->foreign('client_id')->references('client_id')->on('tbl_client');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_document');
    }
};
