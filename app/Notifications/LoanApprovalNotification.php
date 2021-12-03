<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class LoanApprovalNotification extends Notification
{
    use Queueable;
    public $loanApplication, $approval, $old_value, $value;

    /**
     * Create a new notification instance.
     *
     * @param $loanApplication
     * @param $approval
     * @param $old_value
     * @param $value
     */
    public function __construct($loanApplication, $approval, $old_value, $value)
    {
        $this->loanApplication = $loanApplication;
        $this->approval = $approval;
        $this->old_value = $old_value;
        $this->value = $value;
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
            ->subject('Pemberitahuan Persetujuan Pinjaman')
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
                'title'=>  'Pemberitahuan Persetujuan Pinjaman',
                'description'=> 'Perubahan status pinjaman '.$this->loanApplication->approval,
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
            ->setSubject("Pemberitahuan Persetujuan Pinjaman.")
            ->setBody("Cek Sekarang")
            ->setData('type','loan')
            ->setData('id', $this->loanApplication->id)
            ->setData('user_id', $this->loanApplication->member->user_id);
    }
}
