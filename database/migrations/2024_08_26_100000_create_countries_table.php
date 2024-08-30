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
        Schema::create('loginx_countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_native');
            $table->string('phone_code');
            $table->string('code');
            $table->string('flag');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginx_countries');

    }
};
