<?php

namespace App\Events;

use App\Models\Attendance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attendance;  // Menyimpan data absensi

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * Mendefinisikan channel yang akan digunakan untuk broadcast.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Channel pribadi berdasarkan user_id absensi
        return new PrivateChannel('attendance.' . $this->attendance->user_id);
    }

    /**
     * Mendefinisikan nama event yang akan dipancarkan.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'attendance.updated';  // Nama event di frontend
    }
}
