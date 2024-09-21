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
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_created_task');
        Schema::dropIfExists('task');
    }
};
