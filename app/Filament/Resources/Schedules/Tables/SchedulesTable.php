<?php

namespace App\Filament\Resources\Schedules\Tables;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.name')
                    ->label('Ruangan / Lab')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Mata Kuliah / Kegiatan')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('borrower_name')
                    ->label('Dosen Pengampu / PIC')
                    ->searchable(),
                TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Waktu Selesai')
                    ->dateTime('H:i')
                    ->sortable(),
                TextColumn::make('realtime_status')
                    ->label('Kondisi Real-Time')
                    ->state(function ($record) {
                        $now = Carbon::now();
                        if ($record->status === 'cancelled') {
                            return 'Dibatalkan';
                        }
                        if ($now->between($record->start_time, $record->end_time)) {
                            return 'Terpakai Saat Ini';
                        }
                        if ($now->lt($record->start_time)) {
                            return 'Akan Datang';
                        }

                        return 'Selesai';
                    })
                    ->badge()
                    ->colors([
                        'danger' => 'Terpakai Saat Ini',
                        'info' => 'Akan Datang',
                        'gray' => 'Selesai',
                        'warning' => 'Dibatalkan',
                    ]),
            ])
            ->filters([
                SelectFilter::make('room_id')
                    ->label('Filter Ruangan')
                    ->relationship('room', 'name'),
            ])
            ->recordActions([
                Action::make('buka_web')
                    ->label('Halaman Ruang')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => route('rooms.show', $record->room->slug))
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
