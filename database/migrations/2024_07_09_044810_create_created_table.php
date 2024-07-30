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
        if (!Schema::hasTable('tbl_created_task')) {
        Schema::create('tbl_created_task', function (Blueprint $table) {
            $table->id('create_id');
            $table->string('Office_name');
            $table->string('Office_task');
            $table->string('New_alloted_time');
            $table->timestamp('Date_created');
            $table->string('soft_del');
            
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
        Schema::dropIfExists('tbl_created_task');
    }
};
