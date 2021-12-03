<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewResignApplication extends Notification
{
	use Queueable;
	public $resignApplication;
	public $subject;
    public $via;

    /**
     * NewresignApplication constructor.
     * @param $resignApplication
     * @param array $via
     */
	public function __construct($resignApplication, array $via)
	{
		$this->resignApplication = $resignApplication;
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
			->subject('Pengajuan Pengunduran Diri')
			->markdown('mail.loan.new-application',['resignApplication'=> $this->resignApplication]);
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
			'url'=>'resign',
			'content'=> [
				'title' => 'Terdapat Pengunduran Baru',
				'description'=> $this->resignApplication->member->full_name.' mengajukan pengunduran diri',
				'object'=> $this->resignApplication,
				'object_type'=> 'App\Resign'
			],
			'icon'=> 'fa-handshake-o',
			'icon-color'=> 'red'
		];
	}

	public function toOneSignal($notifiable)
	{
		return OneSignalMessage::create()
			->setSubject("Terdapat Pengunduran Diri")
			->setBody("Cek sekarang")
			->setData('type','resign')
			->setData('id', 5)
			->setData('user_id', $this->resignApplication->member['id']);
	}
}
