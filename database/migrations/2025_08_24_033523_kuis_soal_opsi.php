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
        Schema::create('kuis_soal_opsi', function (Blueprint $table) {
            $table->id('kuis_soal_opsi_id');
            $table->foreignId('kuis_soal_id');
            $table->string('jawaban');
            $table->smallInteger('is_kunci_jawaban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuis_soal_opsi');
    }
};
