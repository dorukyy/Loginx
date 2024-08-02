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
        Schema::create('loginx_blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('reason')->nullable();
            $table->string('blocked_by')->nullable();
            $table->dateTime('blocked_until')->nullable();
            $table->string('unblocked_reason')->nullable();
            $table->string('unblocked_by')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginx_blocked_ips');

    }
};
