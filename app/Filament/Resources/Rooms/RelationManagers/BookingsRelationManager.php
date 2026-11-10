<?php

namespace App\Filament\Resources\Rooms\RelationManagers;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $title = 'Jadwal Kuliah & Permohonan Booking Ruangan Ini';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nama Mata Kuliah / Agenda Kegiatan')
                    ->placeholder('Contoh: Pemrograman Sistem Tertanam - Kelas A')
                    ->required()
                    ->maxLength(150),
                TextInput::make('borrower_name')
                    ->label('Dosen Pengampu / Pemohon')
                    ->placeholder('Contoh: Dr. Ir. Hendra, M.T.')
                    ->required()
                    ->maxLength(100),
                TextInput::make('borrower_nim')
                    ->label('NIP / NIM')
                    ->default('DOSEN')
                    ->required(),
                TextInput::make('borrower_phone')
                    ->label('No. WhatsApp')
                    ->default('081234567890')
                    ->required(),
                DateTimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->after('start_time')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'confirmed' => 'Disetujui / Terjadwal',
                        'pending' => 'Pending Approval',
                        'checked_in' => 'Aktif Digunakan',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('confirmed')
                    ->required(),
                Textarea::make('purpose')
                    ->label('Keterangan Tambahan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode')
                    ->weight('bold')
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Matkul / Agenda')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('borrower_name')
                    ->label('Dosen / Pemohon')
                    ->searchable(),
                TextColumn::make('borrower_phone')
                    ->label('WhatsApp'),
                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->dateTime('H:i')
                    ->sortable(),
                TextColumn::make('realtime')
                    ->label('Kondisi Real-Time')
                    ->state(function (Booking $record) {
                        $now = Carbon::now();
                        if ($record->status === 'rejected' || $record->status === 'cancelled') {
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
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'info' => 'checked_in',
                        'gray' => 'completed',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending Approval',
                        'confirmed' => 'Disetujui',
                        'checked_in' => 'Aktif Digunakan',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending Approval',
                        'confirmed' => 'Disetujui',
                        'checked_in' => 'Aktif Digunakan',
                        'completed' => 'Selesai',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Jadwal / Booking')
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        if (empty($data['booking_code'])) {
                            $data['booking_code'] = 'JDW-'.strtoupper(now()->format('Ymd')).'-'.strtoupper(Str::random(4));
                        }

                        return $data;
                    }),
            ])
            ->recordActions([
                Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Booking $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Booking $record) {
                        $record->update(['status' => 'confirmed']);
                        Notification::make()
                            ->title('Jadwal Disetujui')
                            ->success()
                            ->send();
                    }),
                Action::make('wa')
                    ->label('WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('info')
                    ->url(function (Booking $record) {
                        $clean = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $record->borrower_phone));
                        $text = urlencode("Halo {$record->borrower_name}, perihal penggunaan ruangan {$record->room->name} ({$record->booking_code}) di SIMRU Teknik...");

                        return "https://wa.me/{$clean}?text={$text}";
                    })
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
