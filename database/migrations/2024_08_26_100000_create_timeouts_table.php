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
        Schema::create('loginx_timeouts', function (Blueprint $table) {
            $table->string('user_id')->nullable();
            $table->string('ip');
            $table->dateTime('unblocked_at');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginx_timeouts');

    }
};
