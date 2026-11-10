<?php

namespace App\Livewire;

use App\Models\Room;
use App\Services\QrCodeService;
use App\Services\RoomService;
use Livewire\Component;

class RoomBrowser extends Component
{
    public string $search = '';

    public string $statusFilter = '';

    public string $minCapacity = '';

    public int $perPage = 9;

    public function updatedSearch()
    {
        $this->perPage = 9;
    }

    public function updatedStatusFilter()
    {
        $this->perPage = 9;
    }

    public function updatedMinCapacity()
    {
        $this->perPage = 9;
    }

    public function loadMore()
    {
        $this->perPage += 9;
    }

    public function render(RoomService $roomService, QrCodeService $qrService)
    {
        $capacity = $this->minCapacity !== '' ? (int) $this->minCapacity : null;
        $status = $this->statusFilter !== '' ? $this->statusFilter : null;

        $query = Room::query()
            ->when($this->search, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q, $s) => $q->where('status', $s))
            ->when($capacity, fn ($q, $cap) => $q->where('capacity', '>=', $cap))
            ->orderBy('name');

        $totalMatchingRooms = (clone $query)->count();
        $rooms = $query->take($this->perPage)->get();

        $roomsData = $rooms->map(function ($room) use ($roomService, $qrService) {
            $currentStatus = $roomService->getRoomCurrentStatus($room);
            $qrDataUri = $qrService->generateDataUri($qrService->generateRoomPayload($room), 140);

            return [
                'model' => $room,
                'status' => $currentStatus,
                'qr' => $qrDataUri,
            ];
        });

        $hasMore = $totalMatchingRooms > $this->perPage;

        return view('livewire.room-browser', [
            'roomsData' => $roomsData,
            'totalMatchingRooms' => $totalMatchingRooms,
            'hasMore' => $hasMore,
            'showingCount' => min($this->perPage, $totalMatchingRooms),
        ])->layout('layouts.app', ['title' => 'Daftar Ruangan & Lab - SIMRU Teknik']);
    }
}
