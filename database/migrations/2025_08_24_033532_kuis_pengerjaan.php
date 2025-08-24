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
        Schema::create('kuis_pengerjaan', function (Blueprint $table) {
            $table->id('kuis_pengerjaan_id');
            $table->foreignId('user_id');
            $table->foreignId('kuis_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuis_pengerjaan');
    }
};
