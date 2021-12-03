<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewMemberBlastNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $member;
    public $via;
    public $tries = 3; // Max tries
    public $timeout = 15; // Timeout seconds


    /**
     * Create a new notification instance.
     *
     * @param $member
     * @param array $via
     */
    public function __construct($member, array $via)
    {
        $this->member = $member;
        $this->via = $via;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->via;
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
            ->subject($this->member->first_name .'Mendaftar sebagai anggota')
            ->markdown('mail.article.blast');
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
            'url'=>'member',
            'content'=> [
                'title'=>  'Anggota Baru Mendaftar',
                'description'=> Str::limit($this->member->full_name .'Mendaftar sebagai anggota', 25, '...'),
                'object'=> $this->member,
                'object_type'=> 'App\Member'
            ],
            'icon'=> 'user-plus',
            'icon-color'=> 'red'
        ];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject('Anggota Baru Mendaftar')
            // ->setBody(Str::limit($this->article->content, 25, '...'))
            ->setBody(Str::limit($this->member->first_name .'Mendaftar sebagai anggota', 25, '...'))
            ->setData('type','members')
            ->setData('id', $this->member->id);
//			->setImageAttachments('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png');
//            ->setImageAttachments(asset('images/news/'.$this->article->image_name));
        // ->setWebButton(
        //     OneSignalWebButton::create('link-1')
        //         ->text('Click here')
        //         ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
        //         ->url('http://laravel.com')
        // );
    }
}
