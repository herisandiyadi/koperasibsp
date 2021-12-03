<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewLoanNotification extends Notification
{
    use Queueable;
    public $loan;
    public $via;

    /**
     * Create a new notification instance.
     *
     * @param $loan
     * @param array $via
     */
    public function __construct($loan, array $via)
    {
        $this->loan = $loan;
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
            ->subject($this->loan->member->full_name .'Mengajukan pinjaman')
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
            'url'=>'loan',
            'content'=> [
                'title'=> $this->loan->member->full_name.' Mengajukan Pinjaman',
                'description'=> $this->loan->member->full_name.' mengajukan pinjaman',
                'object'=> $this->loan,
                'object_type'=> 'App\TsLoans'
            ],
            'icon'=> 'fa-handshake-o',
            'icon-color'=> 'red'
        ];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject("Terdapat Pengajuan Pinjaman Baru")
            ->setBody("Cek sekarang")
            ->setData('type','loan')
            ->setData('id', $this->loan->id)
            ->setData('user_id', $this->loan->member->user_id);
//            ->setButton(
//                OneSignalButton::create('link-1')
//                    ->text('Click here')
//                    ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
//            );
    }
}
