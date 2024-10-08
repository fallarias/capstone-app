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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id('user_id');
                $table->string('firstname');
                $table->string('lastname');
                $table->string('middlename');
                $table->string('email')->unique();
                $table->string('account_type');
                $table->string('status')->default('Not Accepted');
                $table->string('profile_picture')->nullable();   
                $table->string('is_delete')->default('active');       
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('department');
                $table->rememberToken();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('password_reset_tokens')) {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
             $table->string('email')->primary();
             $table->string('token');
             $table->timestamp('created_at')->nullable();
         });
        }
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->onDelete('cascade')->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // to update users table first delete the sessions table then comment the code for sessions table
        // and update the user then uncomment the code for sessions table and 
        //run php artisan migrate:refresh

        //Schema::dropIfExists('users');
        //Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
