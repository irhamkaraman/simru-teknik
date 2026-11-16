<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Models\Schedule;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
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
                Select::make('day_of_week')
                    ->label('Hari Perkuliahan')
                    ->options(Schedule::DAYS)
                    ->default(1)
                    ->native(false)
                    ->required(),
                TextInput::make('title')
                    ->label('Nama Mata Kuliah / Agenda Jadwal')
                    ->placeholder('Contoh: Pemrograman Sistem Tertanam - Kelas A')
                    ->required()
                    ->maxLength(150),
                TextInput::make('lecturer_name')
                    ->label('Dosen Pengampu / Instruktur / PIC')
                    ->placeholder('Contoh: Dr. Ir. Budi Raharjo, M.T.')
                    ->required()
                    ->maxLength(100),
                TextInput::make('lecturer_code')
                    ->label('NIP / Kode Dosen')
                    ->placeholder('Contoh: NIP. 198501012010121001')
                    ->nullable(),
                TextInput::make('phone')
                    ->label('Nomor Kontak / WhatsApp')
                    ->tel()
                    ->placeholder('Contoh: 081234567890')
                    ->nullable(),
                TimePicker::make('start_time')
                    ->label('Jam Mulai (WIB)')
                    ->seconds(false)
                    ->format('H:i')
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Jam Selesai (WIB)')
                    ->seconds(false)
                    ->format('H:i')
                    ->required(),
                Select::make('status')
                    ->label('Status Jadwal')
                    ->options([
                        'active' => 'Aktif / Berjalan Rutin',
                        'inactive' => 'Nonaktif / Diliburkan',
                    ])
                    ->default('active')
                    ->required(),
                Textarea::make('purpose')
                    ->label('Keterangan / Silabus Singkat')
                    ->placeholder('Contoh: Praktikum mingguan modul mikrokontroler Arduino & ESP32')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
