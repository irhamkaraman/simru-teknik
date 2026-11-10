<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(100),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Select::make('role')
                    ->label('Peran Akun')
                    ->options([
                        'admin' => 'Administrator Teknik',
                        'staff' => 'Staf Laboratorium',
                        'student' => 'Mahasiswa / Dosen',
                    ])
                    ->default('student')
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor Telepon / WhatsApp')
                    ->tel()
                    ->maxLength(20),
                TextInput::make('department')
                    ->label('Program Studi / Departemen')
                    ->maxLength(100),
            ]);
    }
}
