<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('booking')->onDelete('cascade');
            $table->tinyInteger('rating'); // 1-5
            $table->text('komentar')->nullable();
            $table->timestamps();

            // Satu booking hanya boleh diberi 1 ulasan
            $table->unique('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
