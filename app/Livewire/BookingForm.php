<?php

namespace App\Livewire;

use App\Models\Room;
use App\Services\BookingService;
use Carbon\Carbon;
use Livewire\Component;

class BookingForm extends Component
{
    public ?int $room_id = null;

    public string $borrower_name = '';

    public string $borrower_nim = '';

    public string $borrower_phone = '';

    public string $title = '';

    public string $purpose = '';

    public string $booking_date = '';

    public string $start_time = '08:00';

    public string $end_time = '10:00';

    public ?bool $isAvailable = null;

    public string $availabilityMessage = '';

    protected function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'borrower_name' => 'required|string|min:3|max:100',
            'borrower_nim' => 'required|digits:8',
            'borrower_phone' => 'required|string|min:9|max:16',
            'title' => [
                'required',
                'string',
                'min:4',
                function ($attribute, $value, $fail) {
                    $words = count(preg_split('/\s+/', trim((string) $value), -1, PREG_SPLIT_NO_EMPTY));
                    if ($words > 100) {
                        $fail('Nama kegiatan maksimal 100 kata.');
                    }
                },
            ],
            'purpose' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $words = count(preg_split('/\s+/', trim((string) $value), -1, PREG_SPLIT_NO_EMPTY));
                    if ($words > 500) {
                        $fail('Keperluan & catatan maksimal 500 kata.');
                    }
                },
            ],
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }

    protected array $messages = [
        'borrower_nim.digits' => 'NIM wajib tepat 8 digit angka.',
        'borrower_nim.required' => 'NIM wajib diisi.',
        'borrower_name.required' => 'Nama lengkap wajib diisi.',
        'borrower_phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
        'end_time.after' => 'Waktu selesai harus lebih lambat dari waktu mulai.',
    ];

    public function mount(?int $room_id = null)
    {
        $this->room_id = $room_id;
        $this->booking_date = Carbon::today()->format('Y-m-d');

        $this->checkAvailability(app(BookingService::class));
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['room_id', 'booking_date', 'start_time', 'end_time'], true)) {
            $this->checkAvailability(app(BookingService::class));
        }
    }

    public function checkAvailability(BookingService $bookingService): void
    {
        if (! $this->room_id || ! $this->booking_date || ! $this->start_time || ! $this->end_time) {
            $this->isAvailable = null;
            $this->availabilityMessage = '';

            return;
        }

        if ($this->end_time <= $this->start_time) {
            $this->isAvailable = false;
            $this->availabilityMessage = 'Waktu selesai harus lebih lambat dari waktu mulai.';

            return;
        }

        $start = Carbon::parse("{$this->booking_date} {$this->start_time}");
        $end = Carbon::parse("{$this->booking_date} {$this->end_time}");

        $available = $bookingService->isSlotAvailable($this->room_id, $start, $end);
        $this->isAvailable = $available;

        if ($available) {
            $this->availabilityMessage = 'Jadwal ruangan tersedia untuk diajukan reservasi.';
        } else {
            $this->availabilityMessage = 'Ruangan tidak tersedia atau sedang pemeliharaan pada jam tersebut.';
        }
    }

    public function submit(BookingService $bookingService)
    {
        $this->validate();

        $start = Carbon::parse("{$this->booking_date} {$this->start_time}");
        $end = Carbon::parse("{$this->booking_date} {$this->end_time}");

        if (! $bookingService->isSlotAvailable($this->room_id, $start, $end)) {
            session()->flash('error', 'Ruangan tidak tersedia pada jadwal yang dipilih.');

            return;
        }

        $booking = $bookingService->createBooking([
            'room_id' => $this->room_id,
            'borrower_name' => $this->borrower_name,
            'borrower_nim' => $this->borrower_nim,
            'borrower_phone' => $this->borrower_phone,
            'title' => $this->title,
            'purpose' => $this->purpose,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'pending',
        ]);

        session()->flash('success', "Permohonan reservasi ({$booking->booking_code}) berhasil diajukan! Menunggu persetujuan Admin Lab Teknik.");

        return redirect()->route('bookings.index');
    }

    public function render()
    {
        $rooms = Room::available()->orderBy('name')->get();

        $roomOptions = $rooms->map(fn ($r) => [
            'value' => (string) $r->id,
            'label' => "{$r->code} • {$r->name} (Kapasitas: {$r->capacity} org - {$r->location})",
        ])->values()->all();

        return view('livewire.booking-form', [
            'rooms' => $rooms,
            'roomOptions' => $roomOptions,
        ])->layout('layouts.app', ['title' => 'Formulir Pengajuan Reservasi - SIMRU Teknik']);
    }
}
