<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'staff',
                        'info' => 'student',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrator',
                        'staff' => 'Staf Lab',
                        'student' => 'Mahasiswa / Dosen',
                        default => $state,
                    }),
                TextColumn::make('department')
                    ->label('Departemen')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Peran Akun')
                    ->options([
                        'admin' => 'Administrator',
                        'staff' => 'Staf Lab',
                        'student' => 'Mahasiswa / Dosen',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
