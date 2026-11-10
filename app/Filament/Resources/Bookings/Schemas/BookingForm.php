<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
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
                    ->label('Nama Acara / Kegiatan')
                    ->required()
                    ->maxLength(150),
                TextInput::make('borrower_name')
                    ->label('Nama Pemohon')
                    ->required()
                    ->maxLength(100),
                TextInput::make('borrower_nim')
                    ->label('NIM Pemohon (Wajib 8 Digit)')
                    ->length(8)
                    ->required(),
                TextInput::make('borrower_phone')
                    ->label('Nomor WhatsApp')
                    ->tel()
                    ->required(),
                Select::make('status')
                    ->label('Status Reservasi')
                    ->options([
                        'pending' => 'Pending (Menunggu Persetujuan)',
                        'confirmed' => 'Disetujui (Confirmed)',
                        'checked_in' => 'Aktif Digunakan (Checked In)',
                        'completed' => 'Selesai (Completed)',
                        'rejected' => 'Ditolak (Rejected)',
                        'cancelled' => 'Dibatalkan (Cancelled)',
                    ])
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->required(),
                Textarea::make('purpose')
                    ->label('Tujuan / Keperluan')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('cancellation_reason')
                    ->label('Catatan / Alasan Penolakan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
