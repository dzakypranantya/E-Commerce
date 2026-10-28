<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('layanan')) {
            return;
        }

        Schema::create('layanan', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('nama_ekspedisi', 100);
            $table->enum('jenis_layanan', ['Reguler', 'Express', 'Cargo']);
            $table->enum('zonasi', ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4']);
            $table->unsignedInteger('tarif');
            $table->string('estimasi', 100);
            $table->enum('status', ['ON', 'OFF'])->default('ON');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
