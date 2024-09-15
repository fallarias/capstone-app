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
        if (!Schema::hasTable('otp')) {
            Schema::create('otp', function (Blueprint $table) {
                $table->id();
                $table->string('otp_type');
                $table->string('number_or_email');
                $table->string('otp');
                $table->timestamp('expire_at');
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile');
    }
};
