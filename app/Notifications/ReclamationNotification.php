<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReclamationNotification extends Notification
{
    use Queueable;
    protected $reclamation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reclamation)
    {
        $this->reclamation = $reclamation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('notification.email_reclamation_status_subject'))
            ->greeting(trans('notification.email_reclamation_status_subject'))
            ->line(trans('notification.email_reclamation_status_text',['reclamation_number'=>$this->reclamation->id]).trans('reclamation.status_'.$this->reclamation->status))
            ->action(trans('global.detail'), route('reclamations'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'name' => trans('notification.notify_reclamation_status_subject').$this->reclamation->id,
            'text' => trans('notification.notify_reclamation_status_text').trans('reclamation.status_'.$this->reclamation->status),
            'icon' => 'fas fa-exchange-alt text-blue',
            'link' => route('reclamations'),
        ];
    }
}
