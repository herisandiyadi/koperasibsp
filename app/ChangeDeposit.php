<?php

namespace App;

use App\Notifications\NewChangeDepositApplication;
use Illuminate\Database\Eloquent\Model;
use NotificationChannels\OneSignal\OneSignalChannel;

class ChangeDeposit extends Model
{
	protected  $table = 'change_deposits';
	protected $fillable = [
		'member_id',
		'date',
		'name',
		'last_wajib',
		'last_sukarela',
		'new_wajib',
		'new_sukarela',
		'approval'
	];

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
			$user->notify(new NewChangeDepositApplication($this, $via));
		}
	}
}
