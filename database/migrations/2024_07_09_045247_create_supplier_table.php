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
        Schema::create('tbl_supplier', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('supplier_lname');
            $table->string('supplier_fname');
            $table->string('supplier_mname');
            $table->string('address');
            $table->string('type_of_service');
            
            $table->unsignedBigInteger('user_id');
        
            $table->foreign('user_id')->references('user_id')->on('tbl_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
