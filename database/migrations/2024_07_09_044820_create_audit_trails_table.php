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
        if (!Schema::hasTable('audit_trails')) {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id('audit_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('transaction_id');
            $table->timestamp('start')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->timestamp('finished')->nullable();
            $table->string('office_name');
            $table->boolean('email_reminder_sent')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('task_id')->on('task')->onDelete('cascade');
            $table->foreign('transaction_id')->references('transaction_id')->on('tbl_transaction')->onDelete('cascade');
        });
    }
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
