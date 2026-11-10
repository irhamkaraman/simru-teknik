<?php

namespace App\Filament\Resources\Rooms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Ruangan / Laboratorium')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('code')
                    ->label('Kode Ruangan')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(30),
                TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(120),
                TextInput::make('capacity')
                    ->label('Kapasitas Orang')
                    ->numeric()
                    ->required()
                    ->default(20),
                TextInput::make('location')
                    ->label('Lokasi Gedung / Lantai')
                    ->required()
                    ->maxLength(150),
                Select::make('status')
                    ->label('Status Operasional')
                    ->options([
                        'available' => 'Tersedia',
                        'maintenance' => 'Sedang Pemeliharaan',
                    ])
                    ->default('available')
                    ->required(),
                TextInput::make('qr_identifier')
                    ->label('ID Stiker Pintu QR')
                    ->default(fn () => 'room-'.Str::random(10))
                    ->required(),
                TagsInput::make('facilities')
                    ->label('Fasilitas Penunjang')
                    ->placeholder('Ketik fasilitas dan tekan Enter'),
                Textarea::make('description')
                    ->label('Deskripsi Lengkap')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
