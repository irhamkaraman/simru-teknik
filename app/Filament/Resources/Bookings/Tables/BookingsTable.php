<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('room.name')
                    ->label('Ruangan')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('borrower_name')
                    ->label('Pemohon')
                    ->searchable(),
                TextColumn::make('borrower_nim')
                    ->label('NIM (8 Digit)')
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('borrower_phone')
                    ->label('No. WhatsApp')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Agenda Kegiatan')
                    ->limit(25)
                    ->searchable(),
                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->dateTime('H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'info' => 'checked_in',
                        'gray' => 'completed',
                        'danger' => ['rejected', 'cancelled'],
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending Approval',
                        'confirmed' => 'Disetujui',
                        'checked_in' => 'Aktif Digunakan',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending (Perlu Approval)',
                        'confirmed' => 'Disetujui',
                        'checked_in' => 'Aktif Digunakan',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                    ]),
                SelectFilter::make('room_id')
                    ->label('Ruangan')
                    ->relationship('room', 'name'),
            ])
            ->recordActions([
                Action::make('setujui')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Permohonan Reservasi')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui peminjaman ruangan ini?')
                    ->action(function ($record) {
                        $record->update(['status' => 'confirmed']);
                        Notification::make()
                            ->title('Reservasi Disetujui')
                            ->body('Permohonan '.$record->booking_code.' berhasil disetujui.')
                            ->success()
                            ->send();
                    }),
                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Permohonan Reservasi')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Reservasi Ditolak')
                            ->body('Permohonan '.$record->booking_code.' telah ditolak.')
                            ->danger()
                            ->send();
                    }),
                Action::make('hubungi_wa')
                    ->label('Chat WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('info')
                    ->url(function ($record) {
                        $clean = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $record->borrower_phone));
                        $text = urlencode("Halo {$record->borrower_name}, perihal reservasi ruangan {$record->room->name} ({$record->booking_code}) di SIMRU Teknik...");

                        return "https://wa.me/{$clean}?text={$text}";
                    })
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
