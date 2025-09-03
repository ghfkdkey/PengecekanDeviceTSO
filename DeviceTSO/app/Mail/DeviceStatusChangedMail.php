<?php

namespace App\Mail;

use App\Models\DeviceCheckResult;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class DeviceStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $result;
    public $pic;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DeviceCheckResult $result, User $pic)
    {
        $this->result = $result;
        $this->pic = $pic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $device = $this->result->device;
        $room = $device->room;
        $floor = $room->floor;
        $checklistItem = $this->result->checklistItem;
        $checkedByUser = $this->result->user;
        
        $eventDate = now();
        $statusText = Str::ucfirst($this->result->status);
        $subject = "[ALERT] Device {$device->device_name} Berstatus {$statusText}";

        return $this->subject($subject)
                    ->view('emails.device-status-changed')
                    ->with([
                        'picName'        => $this->pic->full_name,
                        'status'         => $this->result->status,
                        'statusText'     => $statusText,
                        'deviceName'     => $device->device_name,
                        'deviceType'     => $device->device_type,
                        'serialNumber'   => $device->serial_number,
                        'location'       => "{$room->room_name}, {$floor->floor_name}",
                        'checklistItem'  => $checklistItem->question,
                        'notes'          => $this->result->notes,
                        'checkedBy'      => $checkedByUser->full_name,
                        'checkedAt'      => $this->result->updated_at_custom->format('d/m/Y H:i'),
                        'updatedAt'      => optional($this->result->updated_at_custom)->format('d/m/Y H:i'),
                    ]);
    }
}