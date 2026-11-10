<?php

namespace App\Livewire;

use App\Models\Room;
use App\Services\QrCodeService;
use App\Services\RoomService;
use Livewire\Component;

class RoomDetail extends Component
{
    public Room $room;

    public function mount(string $slug, RoomService $roomService)
    {
        $this->room = Room::where('slug', $slug)->firstOrFail();
    }

    public function render(RoomService $roomService, QrCodeService $qrService)
    {
        $currentStatus = $roomService->getRoomCurrentStatus($this->room);
        $todaySchedule = $roomService->getTodaySchedule($this->room);
        $upcomingSchedule = $roomService->getUpcomingSchedule($this->room);

        $doorQrUrl = $qrService->generateRoomPayload($this->room);
        $doorQrSvg = $qrService->generateSvg($doorQrUrl, 220);

        return view('livewire.room-detail', [
            'currentStatus' => $currentStatus,
            'todaySchedule' => $todaySchedule,
            'upcomingSchedule' => $upcomingSchedule,
            'doorQrSvg' => $doorQrSvg,
            'doorQrUrl' => $doorQrUrl,
        ])->layout('layouts.app', ['title' => $this->room->name.' - SIMRU Teknik']);
    }
}
