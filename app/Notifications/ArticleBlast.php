<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use Illuminate\Queue\InteractsWithQueue;

class ArticleBlast extends Notification implements ShouldQueue
{
    use Queueable;
    public $article;
	public $via;
	public $tries = 3; // Max tries
    public $timeout = 15; // Timeout seconds


    /**
     * Create a new notification instance.
     *
     * @param $article
     * @param array $via
     */
    public function __construct($article, array $via)
    {
        $this->article = $article;
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
            ->subject($this->article->title)
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
            'url'=>'article',
            'content'=> [
                'title'=>  $this->article->title,
                'description'=> Str::limit($this->article->content, 25, '...'),
                'object'=> $this->article,
                'object_type'=> 'App\Article'
            ],
            'icon'=> 'fa-handshake-o',
            'icon-color'=> 'red'
        ];
    }

    public function toOneSignal($notifiable)
    {
//        dd(url('images/news/'.$this->article->image_name));
        return OneSignalMessage::create()
            ->setSubject($this->article->title)
            // ->setBody(Str::limit($this->article->content, 25, '...'))
            ->setBody('Baca artikelnya di sini ...')
			->setData('type','article')
            ->setData('id_notification', $this->id)
			->setData('id', $this->article->id)
            ->setData('title', $this->article->title)
//            ->setData('content', $this->article->content)
            ->setData('image_name', $this->article->image_name)
            ->setData('publish_at', $this->article->publish_at)
//			->setImageAttachments('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png');
			->setImageAttachments(url('images/news/'.$this->article->image_name));
			// ->setWebButton(
            //     OneSignalWebButton::create('link-1')
            //         ->text('Click here')
            //         ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
            //         ->url('http://laravel.com')
            // );
    }
}
