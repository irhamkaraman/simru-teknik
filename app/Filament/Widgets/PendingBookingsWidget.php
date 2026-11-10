<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingBookingsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where('status', 'pending')
                    ->latest()
            )
            ->heading('Antrean Permohonan Reservasi (Menunggu Persetujuan Admin)')
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->weight('bold')
                    ->fontFamily('mono'),
                TextColumn::make('room.name')
                    ->label('Ruangan Lab')
                    ->weight('bold'),
                TextColumn::make('borrower_name')
                    ->label('Nama Pemohon'),
                TextColumn::make('borrower_nim')
                    ->label('NIM (8 Digit)')
                    ->fontFamily('mono'),
                TextColumn::make('title')
                    ->label('Kegiatan')
                    ->limit(30),
                TextColumn::make('start_time')
                    ->label('Jadwal Mulai')
                    ->dateTime('d M Y, H:i'),
                TextColumn::make('end_time')
                    ->label('Jadwal Selesai')
                    ->dateTime('H:i'),
                TextColumn::make('borrower_phone')
                    ->label('WhatsApp'),
            ])
            ->recordActions([
                Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (Booking $record) {
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
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (Booking $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Reservasi Ditolak')
                            ->body('Permohonan '.$record->booking_code.' ditolak.')
                            ->danger()
                            ->send();
                    }),
                Action::make('wa')
                    ->label('Chat WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('info')
                    ->url(function (Booking $record) {
                        $clean = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $record->borrower_phone));
                        $text = urlencode("Halo {$record->borrower_name}, perihal reservasi ruangan {$record->room->name} ({$record->booking_code}) di SIMRU Teknik...");

                        return "https://wa.me/{$clean}?text={$text}";
                    })
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Tidak Ada Permohonan Pending')
            ->emptyStateDescription('Seluruh pengajuan reservasi laboratorium telah diproses.');
    }
}
