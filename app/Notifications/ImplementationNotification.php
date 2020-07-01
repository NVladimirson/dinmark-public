<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImplementationNotification extends Notification
{
    use Queueable;
    protected $implementation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($implementation)
    {
        $this->implementation = $implementation;
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
        $message = (new MailMessage)
            ->subject(trans('notification.implementation_subject').$this->implementation->public_number)
            ->greeting(trans('notification.implementation_subject').$this->implementation->public_number)
            ->line(trans('notification.implementation_text').$this->implementation->ttn);

        $message = $message->action(trans('global.detail'), route('implementations'));

        return $message;
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
            'name' => trans('notification.implementation_subject').$this->implementation->public_number,
            'text' => trans('notification.implementation_text').$this->implementation->ttn,
            'icon' => 'fas fa-shopping-cart text-green',
            'link' => route('implementations'),
        ];
    }
}
