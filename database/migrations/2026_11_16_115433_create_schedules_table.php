<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week')->comment('1: Senin, 2: Selasa, 3: Rabu, 4: Kamis, 5: Jumat, 6: Sabtu, 7: Minggu');
            $table->time('start_time')->comment('Format Jam:Menit, contoh 08:00:00');
            $table->time('end_time')->comment('Format Jam:Menit, contoh 10:30:00');
            $table->string('title')->comment('Nama Mata Kuliah / Praktikum / Agenda');
            $table->string('lecturer_name')->comment('Dosen Pengampu / Instruktur / PIC');
            $table->string('lecturer_code')->nullable()->comment('NIP / Kode Dosen');
            $table->string('phone')->nullable()->comment('Nomor Kontak / WhatsApp');
            $table->string('status')->default('active')->comment('active: Aktif Rutin, inactive: Nonaktif');
            $table->text('purpose')->nullable()->comment('Keterangan / Silabus Singkat');
            $table->timestamps();

            $table->index(['room_id', 'day_of_week', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
