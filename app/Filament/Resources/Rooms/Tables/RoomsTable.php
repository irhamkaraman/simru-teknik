<?php

namespace App\Filament\Resources\Rooms\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Ruangan')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' Org')
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'available',
                        'danger' => 'maintenance',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Tersedia',
                        'maintenance' => 'Pemeliharaan',
                        default => $state,
                    }),
                TextColumn::make('qr_identifier')
                    ->label('QR ID')
                    ->copyable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'maintenance' => 'Pemeliharaan',
                    ]),
            ])
            ->recordActions([
                Action::make('cetak_qr')
                    ->label('Cetak QR Pintu')
                    ->icon('heroicon-o-qr-code')
                    ->color('warning')
                    ->modalHeading(fn ($record) => 'Stiker QR Pintu: '.$record->name)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.room-qr-sticker', ['record' => $record])),
                Action::make('buka_web')
                    ->label('Lihat Web')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => route('rooms.show', $record->slug))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
