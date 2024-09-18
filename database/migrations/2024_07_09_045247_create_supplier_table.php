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
        if (!Schema::hasTable('tbl_supplier')) {
        Schema::create('tbl_supplier', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('address');
            $table->string('type_of_service');
            $table->string('service_desc');
            
            $table->unsignedBigInteger('user_id');
        
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_supplier');
        
    }
};
