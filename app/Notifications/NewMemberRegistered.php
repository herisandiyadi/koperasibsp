<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMemberRegistered extends Notification
{
    use Queueable;
    public $member;
    public $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($member, $subject = null)
    {
        $this->member = $member;
        if(is_null($subject)){
            $this->subject = "Anggota baru telah terdaftar";
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
            ->subject($this->subject)
            ->markdown('mail.member.new', ['member'=> $this->member]);
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
                'title'=>'Anggota Baru Telah Terdaftar',
                'description'=> $this->member->full_name.' telah mendaftar sebagai anggota baru',
                'object'=> $this->member,
                'object_type'=> 'App\Member'
            ],
            'icon'=> 'fa-user',
            'icon-color'=> 'red'
        ];
    }

}
