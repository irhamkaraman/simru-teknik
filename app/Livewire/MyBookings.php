<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class MyBookings extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filter = 'all';

    public string $roomFilter = '';

    public int $perPage = 8;

    public ?Booking $selectedBooking = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingRoomFilter()
    {
        $this->resetPage();
    }

    public function showDetail(int $id)
    {
        $this->selectedBooking = Booking::with(['room'])->find($id);
    }

    public function closeDetail()
    {
        $this->selectedBooking = null;
    }

    public function render()
    {
        $now = Carbon::now();
        $query = Booking::with(['room', 'user'])->latest('start_time');

        if ($this->filter === 'ongoing') {
            $query->whereIn('status', ['confirmed', 'checked_in'])
                ->where('start_time', '<=', $now)
                ->where('end_time', '>', $now);
        } elseif ($this->filter === 'upcoming') {
            $query->whereIn('status', ['confirmed', 'checked_in'])
                ->where('start_time', '>', $now);
        } elseif ($this->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->filter === 'completed') {
            $query->where(function ($q) use ($now) {
                $q->whereIn('status', ['completed', 'cancelled', 'rejected'])
                    ->orWhere('end_time', '<=', $now);
            });
        }

        if (! empty($this->search)) {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('borrower_name', 'like', "%{$search}%")
                    ->orWhere('borrower_nim', 'like', "%{$search}%")
                    ->orWhere('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('room', function ($inner) use ($search) {
                        $inner->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($this->roomFilter)) {
            $query->where('room_id', $this->roomFilter);
        }

        $bookings = $query->paginate($this->perPage);
        $rooms = Room::orderBy('name')->get();

        return view('livewire.my-bookings', [
            'bookings' => $bookings,
            'rooms' => $rooms,
            'now' => $now,
        ])->layout('layouts.app', ['title' => 'Jadwal Pemakaian Ruangan - SIMRU Teknik']);
    }
}
