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
        if (!Schema::hasTable('task')) {
            Schema::create("task", function (Blueprint $table) {
                $table->id('task_id');
                $table->string("name");
                $table->timestamp("date");
                $table->integer("status")->default(0);
                $table->integer("soft_del")->default(0);
                $table->String('filename')->nullable();
                $table->String('filepath')->nullable();
                $table->integer('size')->nullable();
                $table->string('type')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('tbl_created_task')) {
        Schema::create('tbl_created_task', function (Blueprint $table) {
            $table->id('create_id');
            $table->string('Office_name');
            $table->string('Office_task');
            $table->string('New_alloted_time');
            $table->timestamp('Date_created');
            $table->string('soft_del');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
        
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('task_id')->on('task')->onDelete('cascade');
            $table->timestamps();
        });
    }
    if (!Schema::hasTable('tbl_transaction')) {
        Schema::create('tbl_transaction', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->integer('Total_Office_of_Request');
            $table->integer('Office_Done')->default(0);
            $table->string('status')->default('ongoing');
            $table->timestamps();

            // Add foreign key constraints if needed
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('task_id')->on('task')->onDelete('cascade');
           
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_created_task');
        Schema::dropIfExists('tbl_transaction');
        Schema::dropIfExists('task');
    }
};
