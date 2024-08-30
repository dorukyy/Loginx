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
        Schema::create('loginx_timezones', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('tz_code');
            $table->string('name');
            $table->string('utc');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginx_timezones');

    }
};
