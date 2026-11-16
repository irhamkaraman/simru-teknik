<?php

namespace App\Filament\Resources\Schedules\Tables;

use App\Models\Schedule;
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
                TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Schedule::DAYS[(int) $state] ?? '-')
                    ->colors([
                        'primary' => fn ($state) => in_array((int) $state, [1, 2, 3, 4, 5]),
                        'warning' => fn ($state) => in_array((int) $state, [6, 7]),
                    ])
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Jam Kuliah')
                    ->formatStateUsing(fn ($record) => $record->formatted_time)
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Mata Kuliah / Kegiatan')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('lecturer_name')
                    ->label('Dosen Pengampu / PIC')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Aktif' : 'Nonaktif')
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                    ]),
                TextColumn::make('realtime_status')
                    ->label('Kondisi Real-Time')
                    ->state(function (Schedule $record) {
                        if ($record->status !== 'active') {
                            return 'Nonaktif';
                        }

                        $now = Carbon::now();
                        $currentDayIso = $now->dayOfWeekIso;

                        if ($currentDayIso !== (int) $record->day_of_week) {
                            return 'Jadwal '.($record->day_name);
                        }

                        $currentTime = $now->format('H:i:s');
                        $startTime = Carbon::parse($record->start_time)->format('H:i:s');
                        $endTime = Carbon::parse($record->end_time)->format('H:i:s');

                        if ($currentTime >= $startTime && $currentTime < $endTime) {
                            return 'Terpakai Saat Ini';
                        }

                        if ($currentTime < $startTime) {
                            return 'Hari Ini (Akan Datang)';
                        }

                        return 'Hari Ini (Selesai)';
                    })
                    ->badge()
                    ->colors([
                        'danger' => 'Terpakai Saat Ini',
                        'info' => 'Hari Ini (Akan Datang)',
                        'gray' => fn ($state) => in_array($state, ['Hari Ini (Selesai)', 'Nonaktif']) || str_starts_with((string) $state, 'Jadwal '),
                    ]),
            ])
            ->defaultSort('day_of_week')
            ->filters([
                SelectFilter::make('day_of_week')
                    ->label('Filter Hari')
                    ->options(Schedule::DAYS),
                SelectFilter::make('room_id')
                    ->label('Filter Ruangan')
                    ->relationship('room', 'name'),
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ]),
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
