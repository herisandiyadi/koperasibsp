<?php

namespace App;

use App\Notifications\ArticleBlast;
use App\Notifications\NewResignApplication;
use Illuminate\Database\Eloquent\Model;
use NotificationChannels\OneSignal\OneSignalChannel;

class Resign extends Model
{
    protected $table='resigns';

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

	/**
	 * @param $users
	 * @param array $via can be mail, array, database. for onesignal please add OneSignalChannel::class
	 */
	public function blastTo($users, $via = [OneSignalChannel::class])
	{
		foreach ($users as $user)
		{
			$user->notify(new NewResignApplication($this, $via));
		}
	}

    public static function getMemberAreaCount($region){
        $selected = self::whereHas('member', function ($query) {
            return $query->where('is_active', 0);
        });
        if(!empty($region)){
            $selected = self::whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id'])->where('is_active', 0);
            });
        }
        return $selected;
    }

    public function newResignBlastTo($users, $via = [OneSignalChannel::class])
    {
        foreach ($users as $user)
        {
            $user->notify(new NewResignApplication($this, $via));
        }
    }
}
