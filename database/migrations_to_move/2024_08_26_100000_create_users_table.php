<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('username')->unique()->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('email')->unique();
            $table->date('birthdate')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->foreignId('referrer_id')->default(0);
            $table->string('preferred_language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->string('preferred_date_format')->default('Y-m-d');
            $table->string('referral_code')->unique();
            $table->foreignId('country_id')->default(0);
            $table->text('address')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->timestamp('blocked_until')->nullable();
            $table->string('blocked_reason')->nullable();
            $table->foreignId('blocked_by_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
