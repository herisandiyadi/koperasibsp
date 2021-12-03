<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;

class NewContactNotification extends Notification
{
    use Queueable;
    public $pesan;

    /**
     * Create a new notification instance.
     *
     * @param $pesan
     */
    public function __construct($pesan)
    {
        $this->pesan = $pesan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database', OneSignalChannel::class];
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
            ->from($this->pesan['email'])
            ->subject($this->pesan['judul'])
            ->greeting('Hello! '. $this->pesan->toId->name)
            ->line('Pesan Dari : '. $this->pesan->fromId->name)
            ->line($this->pesan['pesan'])
            ->line('Thank you for using our application!');
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
            'url'=>'contact/'.$this->pesan['id'].'/show',
            'content'=> [
                'title'=>'Terdapat Pesan Baru',
                'description'=> $this->pesan['pesan'],
                'object'=> $this->pesan,
                'object_type'=> 'App\Contact'
            ],
            'icon'=> 'fa-envelope-o',
            'icon-color'=> 'red'
        ];
    }
}
