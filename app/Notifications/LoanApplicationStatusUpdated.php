<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class LoanApplicationStatusUpdated extends Notification
{
    use Queueable;
    public $loanApplication;
    /**
     * Create a new notification instance.
     *
     * LoanApplicationStatusUpdated constructor.
     * @param $loanApplication
     */
    public function __construct($loanApplication)
    {
        $this->loanApplication = $loanApplication;
    }

    /**
    * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database',OneSignalChannel::class];
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
            ->subject('Pemberitahuan Pengajuan Pinjaman')
            ->markdown('mail.loan.status-updated',['loanApplication'=> $this->loanApplication]);
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
                'title'=>  'Pemberitahuan Pengajuan Pinjaman',
                'description'=> 'Perubahan status pengajuan pinjaman',
                'object'=> $this->loanApplication,
                'object_type'=> 'App\TsLoans'
            ],
            'icon'=> 'fa-handshake-o',
            'icon-color'=> 'red'
        ];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject("Pemberitahuan Pengajuan Pinjaman.")
            ->setBody("Cek Sekarang")
            ->setData('type','loan')
            ->setData('id', $this->loanApplication->id)
            ->setData('user_id', $this->loanApplication->member->user_id);
    }
}
