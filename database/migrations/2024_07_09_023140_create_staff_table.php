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
    //     if (!Schema::hasTable('tbl_staff')) {
    //     Schema::create('tbl_staff', function (Blueprint $table) {
    //         $table->id('staff_id');
    //         $table->string('Employee_lname');
    //         $table->string('Employee_fname');
    //         $table->string('Employee_mname');
    //         $table->string('Office_name');
    //         $table->string('Allotted_time');
    //         $table->string('Task');
    //         $table->string('task_details');

            
    //         $table->unsignedBigInteger('user_id');
        
    //         $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
    //         $table->timestamps();
    //     });
    // }
    if (!Schema::hasTable('tbl_qrcode')) {
        Schema::create('tbl_qrcode', function (Blueprint $table) {
            $table->id('qrcode_id');
            $table->dateTime('time_in');
            $table->dateTime('time_out');
            $table->unsignedBigInteger('staff_id');
 
            $table->foreign('staff_id')->references('staff_id')->on('tbl_staff')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('tbl_staff');
        Schema::dropIfExists('tbl_qrcode');
    }
};
