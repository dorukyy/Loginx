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
        Schema::create('loginx_logins', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->text('user_agent');
            $table->text('headers');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('found_type');
            $table->string('user_input');
            $table->foreignId('user_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginx_logins');

    }
};
