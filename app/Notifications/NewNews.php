<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewNews extends Notification
{
    use Queueable;
    protected $news;
    protected $content;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($news,$content)
    {
        $this->news = $news;
        $this->content = $content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if($notifiable->s_newsletter == 1 && $notifiable->last_login > 0){
            return ['database','mail'];
        }else{
            return ['database'];
        }
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
                ->subject($this->content->name)
                ->greeting($this->content->name)
                ->line($this->content->list)
                ->action(trans('global.detail'), route('news.show',[$this->news->id]));
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
            'name' => $this->content->name,
            'text' => mb_strimwidth($this->content->list,0,100,'...'),
            'icon' => 'far fa-newspaper text-yellow',
            'link' => route('news.show',[$this->news->id]),
        ];
    }
}
