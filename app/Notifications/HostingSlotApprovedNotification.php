<?php

namespace App\Notifications;

use App\Models\HostingSlotReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HostingSlotApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Hosting Slot Reservation Approved')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your hosting slot reservation has been approved!")
            ->line("**Day:** {$dayName}")
            ->line("**Time:** {$hostingTime->from_time} - {$hostingTime->to_time}")
            ->when($this->reservation->admin_notes, function ($message) {
                return $message->line("**Admin Notes:** {$this->reservation->admin_notes}");
            })
            ->action('View Reservation', url('/api/v1/hosting/my-reservations'))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $hostingTime = $this->reservation->hostingTime;

        return [
            'type' => 'hosting_slot_approved',
            'reservation_id' => $this->reservation->id,
            'message' => "Your hosting slot reservation for {$hostingTime->day} from {$hostingTime->from_time} to {$hostingTime->to_time} has been approved.",
            'day' => $hostingTime->day,
            'from_time' => $hostingTime->from_time,
            'to_time' => $hostingTime->to_time,
            'admin_notes' => $this->reservation->admin_notes,
        ];
    }
}
