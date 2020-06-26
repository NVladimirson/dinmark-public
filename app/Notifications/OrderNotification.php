<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;
    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
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
            ->subject(trans('notification.email_order_status_subject'))
            ->greeting(trans('notification.email_order_status_subject'))
            ->line(trans('notification.email_order_status_text',['order_status_text'=>$this->order->id.' / '.(($this->order->public_number)?$this->order->public_number:'-')]).$this->order->getStatus->name)
            ->action(trans('global.detail'), route('orders.show',[$this->order->id]));
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
            'name' => trans('notification.notify_order_status_subject').$this->order->id.' / '.(($this->order->public_number)?$this->order->public_number:'-'),
            'text' => trans('notification.notify_order_status_text').$this->order->getStatus->name,
            'icon' => 'fas fa-shopping-cart text-green',
            'link' => route('orders.show',[$this->order->id]),
        ];
    }
}
