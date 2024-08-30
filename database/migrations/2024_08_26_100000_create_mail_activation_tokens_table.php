<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loginx_mail_activation_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->default(Str::random(60))->unique();
            $table->foreignId('user_id');
            $table->timestamp('expires_at')->default(now()->addMinutes(15));
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginx_mail_activation_tokens');

    }
};
