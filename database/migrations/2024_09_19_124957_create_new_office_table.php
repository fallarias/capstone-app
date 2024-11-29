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
        if (!Schema::hasTable('offices')) {
            Schema::create('offices', function (Blueprint $table) {
                $table->id();
                $table->string('department');
                $table->string('target_department');
                $table->string('message');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('requirements')) {
            Schema::create('requirements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('transaction_id');
                $table->string('message');
                $table->string('department');
                $table->timestamp('stop_transaction')->nullable();
                $table->timestamp('resume_transaction')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
                $table->foreign('transaction_id')->references('transaction_id')->on('tbl_transaction')->onDelete('cascade');
                
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
        Schema::dropIfExists('requirements');
    }
};
