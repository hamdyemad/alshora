<?php

namespace App\Notifications;

use App\Models\HostingSlotReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HostingSlotRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public HostingSlotReservation $reservation)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $hostingTime = $this->reservation->hostingTime;
        $dayName = $this->reservation->hostingTime->day;

        return (new MailMessage)
            ->subject('Hosting Slot Reservation Rejected')
            ->greeting("Hello {$notifiable->name},")
            ->line("Unfortunately, your hosting slot reservation has been rejected.")
            ->line("**Day:** {$dayName}")
            ->line("**Time:** {$hostingTime->from_time} - {$hostingTime->to_time}")
            ->when($this->reservation->admin_notes, function ($message) {
                return $message->line("**Reason:** {$this->reservation->admin_notes}");
            })
            ->action('Request Another Slot', url('/api/v1/hosting/available-slots'))
            ->line('You can request another hosting slot at any time.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $hostingTime = $this->reservation->hostingTime;

        return [
            'type' => 'hosting_slot_rejected',
            'reservation_id' => $this->reservation->id,
            'message' => "Your hosting slot reservation for {$hostingTime->day} from {$hostingTime->from_time} to {$hostingTime->to_time} has been rejected.",
            'day' => $hostingTime->day,
            'from_time' => $hostingTime->from_time,
            'to_time' => $hostingTime->to_time,
            'reason' => $this->reservation->admin_notes,
        ];
    }
}
