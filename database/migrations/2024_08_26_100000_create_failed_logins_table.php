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
        Schema::create('loginx_failed_logins', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('user_input');
            $table->integer('user_id')->nullable();
            $table->boolean('is_found');
            $table->string('found_type')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginx_settings');

    }
};
