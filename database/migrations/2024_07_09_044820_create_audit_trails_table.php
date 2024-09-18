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
            $table->integer('account_id');
            $table->string('account_type');
            $table->timestamp('log_date');
            $table->string('log_type');
            $table->string('log_message');
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
