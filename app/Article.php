<?php

namespace App;

use App\Notifications\ArticleBlast;
use Illuminate\Database\Eloquent\Model;
use NotificationChannels\OneSignal\OneSignalChannel;

class Article extends Model
{
	//
	protected $table='articles';
	protected $fillable = ['title','content','isShow','published_at'];
	protected $casts = [
	    'tags'=> 'array',
        'isShow'=> 'boolean'
    ];
    protected $dates = ['published_at'];

    /**
     * @param $users
     * @param array $via can be mail, array, database. for onesignal please add OneSignalChannel::class
     */
	public function blastTo($users, $via = [OneSignalChannel::class])
    {
        foreach ($users as $user)
        {
            $user->notify(new ArticleBlast($this, $via));
        }
    }

    public function isPublished()
    {
        return $this->published_at !== null && now()->gte($this->published_at);
	}

	public function scopeFByTags($query, $tags = [])
	{
		if(!is_array($tags))
		{
			$tags = [$tags];
		}
		return $query->whereJsonContains('tags', $tags);
	}

	public function getImagePath()
    {
        if($this->image_name != '')
        {
            return public_path('images/news/'.$this->image_name);
        }
        return false;
    }
}
