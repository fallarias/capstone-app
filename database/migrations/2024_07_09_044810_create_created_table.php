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
        Schema::create('tbl_created_task', function (Blueprint $table) {
            $table->id('create_id');
            $table->string('admin_lname');
            $table->string('admin_fname');
            $table->string('admin_mname');
            $table->string('Office_task');
            $table->string('New_alloted_time');
            $table->timestamp('Date_created');
            
            $table->unsignedBigInteger('admin_id');
        
            $table->foreign('admin_id')->references('admin_id')->on('tbl_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('created');
    }
};
