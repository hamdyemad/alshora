<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendAgendaNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-agenda-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for agendas based on notification_days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $firebaseService = new \App\Services\FirebaseNotificationService();

        // 1. Judicial Agendas
        $agendas = \App\Models\Agenda::where('is_notified', false)
            ->where('notification_days', '>', 0)
            ->get();

        foreach ($agendas as $agenda) {
            $notificationDate = \Carbon\Carbon::parse($agenda->datetime)->subDays($agenda->notification_days);
            
            if (\Carbon\Carbon::now()->greaterThanOrEqualTo($notificationDate)) {
                $user = $agenda->user;
                if ($user && $user->fcm_token) {
                    $firebaseService->sendToDevice(
                        $user->fcm_token,
                        __('notification.agenda_reminder_title'),
                        __('notification.agenda_reminder_body', [
                            'subject' => $agenda->action_subject,
                            'days' => $agenda->notification_days,
                            'date' => $agenda->datetime->format('Y-m-d H:i')
                        ]),
                        [
                            'type' => 'agenda_reminder',
                            'agenda_id' => (string)$agenda->id
                        ]
                    );
                }
                $agenda->is_notified = true;
                $agenda->save();
            }
        }

        // 2. Preparer Agendas
        $preparerAgendas = \App\Models\PreparerAgenda::where('is_notified', false)
            ->where('notification_days', '>', 0)
            ->get();

        foreach ($preparerAgendas as $pAgenda) {
            $notificationDate = \Carbon\Carbon::parse($pAgenda->datetime)->subDays($pAgenda->notification_days);
            
            if (\Carbon\Carbon::now()->greaterThanOrEqualTo($notificationDate)) {
                $user = $pAgenda->user;
                if ($user && $user->fcm_token) {
                    $firebaseService->sendToDevice(
                        $user->fcm_token,
                        __('agenda.notification_reminder_subject'),
                        __('notification.agenda_reminder_body', [
                            'subject' => $pAgenda->paper_number,
                            'days' => $pAgenda->notification_days,
                            'date' => $pAgenda->datetime->format('Y-m-d H:i')
                        ]),
                        [
                            'type' => 'preparer_agenda_reminder',
                            'agenda_id' => (string)$pAgenda->id
                        ]
                    );
                }
                $pAgenda->is_notified = true;
                $pAgenda->save();
            }
        }
        
        $this->info('All agenda notifications sent successfully.');
    }
}
