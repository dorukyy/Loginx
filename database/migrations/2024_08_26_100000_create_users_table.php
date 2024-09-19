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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'uuid'))
                $table->uuid('uuid')->unique()->default(Str::uuid());
            if (!Schema::hasColumn('users', 'name'))
                $table->string('name');
            if (!Schema::hasColumn('users', 'surname'))
                $table->string('surname');
            if (!Schema::hasColumn('users', 'username'))
                $table->string('surname');
            if (!Schema::hasColumn('users', 'phone'))
                $table->string('phone');
            if (!Schema::hasColumn('users', 'email'))
                $table->string('email');
            if (!Schema::hasColumn('users', 'birthdate'))
                $table->date('birthdate')->nullable();
            if (!Schema::hasColumn('users', 'password'))
                $table->string('password');
            if (!Schema::hasColumn('users', 'avatar'))
                $table->string('avatar')->nullable();
            if (!Schema::hasColumn('users', 'referrer_id'))
                $table->foreignId('referrer_id')->default(0);
            if (!Schema::hasColumn('users', 'preferred_language'))
                $table->string('preferred_language')->default('en');
            if (!Schema::hasColumn('users', 'timezone'))
                $table->string('timezone')->default('UTC');
            if (!Schema::hasColumn('users', 'preferred_date_format'))
                $table->string('preferred_date_format')->default('Y-m-d');
            if (!Schema::hasColumn('users', 'referral_code'))
                $table->string('referral_code')->unique();
            if (!Schema::hasColumn('users', 'country_id'))
                $table->foreignId('country_id')->default(0);
            if (!Schema::hasColumn('users', 'address'))
                $table->text('address')->nullable();
            if (!Schema::hasColumn('users', 'blocked_at'))
                $table->timestamp('blocked_at')->nullable();
            if (!Schema::hasColumn('users', 'blocked_until'))
                $table->timestamp('blocked_until')->nullable();
            if (!Schema::hasColumn('users', 'blocked_reason'))
                $table->string('blocked_reason')->nullable();
            if (!Schema::hasColumn('users', 'blocked_by_id'))
                $table->foreignId('blocked_by_id')->nullable();
            if (!Schema::hasColumn('users', 'email_verified_at'))
                $table->timestamp('email_verified_at')->nullable();
            if (!Schema::hasColumn('users', 'remember_token'))
                $table->rememberToken();
            if(!Schema::hasColumn('users', 'created_at') && !Schema::hasColumn('users', 'updated_at'))
                $table->timestamps();
            if(!Schema::hasColumn('users', 'deleted_at'))
                $table->softDeletes();
        });


        Schema::table('sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('sessions', 'id'))
                $table->string('id')->primary();
            if (!Schema::hasColumn('sessions', 'user_id'))
                $table->foreignId('user_id')->nullable()->index();
            if (!Schema::hasColumn('sessions', 'ip_address'))
                $table->string('ip_address', 45)->nullable();
            if (!Schema::hasColumn('sessions', 'user_agent'))
                $table->text('user_agent')->nullable();
            if (!Schema::hasColumn('sessions', 'payload'))
                $table->longText('payload');
            if (!Schema::hasColumn('sessions', 'last_activity'))
                $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('surname');
            $table->dropColumn('username');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('birthdate');
            $table->dropColumn('password');
            $table->dropColumn('avatar');
            $table->dropColumn('referrer_id');
            $table->dropColumn('preferred_language');
            $table->dropColumn('timezone');
            $table->dropColumn('preferred_date_format');
            $table->dropColumn('referral_code');
            $table->dropColumn('country_id');
            $table->dropColumn('address');
            $table->dropColumn('blocked_at');
            $table->dropColumn('blocked_until');
            $table->dropColumn('blocked_reason');
            $table->foreignId('blocked_by_id');
            $table->dropColumn('email_verified_at');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::dropIfExists('sessions');
    }
};
