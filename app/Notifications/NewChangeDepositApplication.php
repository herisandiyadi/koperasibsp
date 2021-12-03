<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewChangeDepositApplication extends Notification
{
	use Queueable;
	public $changeDepositApplication;
	public $subject, $via;

    /**
     * NewchangeDepositApplication constructor.
     * @param $changeDepositApplication
     * @param $via
     */
	public function __construct($changeDepositApplication, $via)
	{
		$this->changeDepositApplication = $changeDepositApplication;
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
			->subject('Pengajuan Perubahan Simpanan Baru')
			->markdown('mail.loan.new-application',['changeDepositApplication'=> $this->changeDepositApplication]);
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
			'url'=>'change-deposit',
			'content'=> [
				'title'=>'Terdapat Perubahan Simapan Baru',
				'description'=> 'mengajukan perubahan simpanan',
				'object'=> $this->changeDepositApplication,
				'object_type'=> 'App\ChangeDeposit'
			],
			'icon'=> 'fa-handshake-o',
			'icon-color'=> 'red'
		];
	}

	public function toOneSignal($notifiable)
	{
		return OneSignalMessage::create()
			->setSubject("Terdapat Perubahan Simpanan")
			->setBody("Cek sekarang")
			->setData('type','deposit')
			->setData('id', 5)
			->setData('user_id', $this->changeDepositApplication->member['id']);
	}
}
