<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('room_id')
                    ->label('Ruangan / Laboratorium')
                    ->relationship('room', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('title')
                    ->label('Nama Mata Kuliah / Agenda Jadwal')
                    ->placeholder('Contoh: Pemrograman Sistem Tertanam - Kelas A')
                    ->required()
                    ->maxLength(150),
                TextInput::make('borrower_name')
                    ->label('Dosen Pengampu / Instruktur / PIC')
                    ->placeholder('Contoh: Dr. Ir. Budi Raharjo, M.T.')
                    ->required()
                    ->maxLength(100),
                TextInput::make('borrower_nim')
                    ->label('NIP / Kode Dosen')
                    ->default('DOSEN')
                    ->required(),
                TextInput::make('borrower_phone')
                    ->label('Nomor Kontak / WhatsApp')
                    ->tel()
                    ->default('081234567890')
                    ->required(),
                DateTimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->after('start_time')
                    ->required(),
                Select::make('status')
                    ->label('Status Jadwal')
                    ->options([
                        'confirmed' => 'Aktif / Terjadwal',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('confirmed')
                    ->required(),
                Textarea::make('purpose')
                    ->label('Keterangan / Silabus Singkat')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
