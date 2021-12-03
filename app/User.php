<?php

namespace App;

use App\Notifications\ArticleBlast;
use App\Notifications\NewContactNotification;
use App\Notifications\NewMemberBlastNotification;
use Approval\Models\Approval;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use NotificationChannels\OneSignal\OneSignalChannel;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use function foo\func;

class User extends Authenticatable implements JWTSubject
{
	use Notifiable;
	use HasRoles;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'username', 'position_id', 'region_id', 'remember_token'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

//	public function setPasswordAttribute($value)
//	{
//		$this->attributes['password'] = Hash::make($value);
//	}

    // Rest omitted for brevity

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}

    public function routeNotificationForOneSignal()
    {
        if(is_null($this->os_token))
        {
            return '';
        }
        return $this->os_token;
    }

	public function notifyWithOneSignal()
    {
        \OneSignal::sendNotificationToUser(
            "Some Message",
            $this->one_token,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
    }

	public function position()
	{
		return $this->belongsTo(Position::class, 'position_id');
	}

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

	public function available()
	{
		return $this->position()->has('privilege');
	}

	public function member()
	{
		return $this->hasOne(Member::class);
	}

	public function activeMember()
	{
		return $this->member()->fActive();
	}

	public function scopeFNoSuper($query)
    {
        return $query->whereHas('position', function($q){
            $q->fNoSuper();
        });
	}

	public function scopeFNoPower($query)
    {
        return $query->whereHas('position', function($q){
            $q->fNoPower();
        });
	}

	public function scopeFMemberOnly($query, $true = true)
	{
		if($true)
		{
			return $query->has('member');
		}
		return $query->doesntHave('member');
	}

    public function scopeFAdminRegister(Builder $query){
        return $query->whereHas('position', function (Builder $q) {
            $q->whereHas('level', function (Builder $q2) {
                $q2->where('name', config('auth.level.ADMINKOPERASI.name'))
                ->orWhere('name', config('auth.level.SUPERADMIN.name'))
                ->orWhere('name', config('auth.level.POWERADMIN.name'));
            });
        });
    }

    public function scopeFDansekArea(Builder $query){
	    return $query->where('region_id', auth()->user()->member->region->id)->whereHas('position', function (Builder $q){
            $q->whereHas('level', function (Builder $q2) {
                $q2->where('name', config('auth.level.DANSEK.name'));
            });
        });
    }

    public function scopeFAdminArea(Builder $query){
        return $query->where('region_id', auth()->user()->member->region->id)->whereHas('position', function (Builder $q){
            $q->whereHas('level', function (Builder $q2) {
                $q2->where('name', config('auth.level.ADMINAREA.name'));
            });
        });
    }

    public function scopeFUserApproval(Builder $query){
        return $query->WhereHas('position', function (Builder $q) {
                $q->whereHas('level', function (Builder $q2) {
                    $q2->where('name', config('auth.level.ADMINKOPERASI.name'));
//                        ->orWhere('name', config('auth.level.SUPERADMIN.name'))
//                        ->orWhere('name', config('auth.level.POWERADMIN.name'));
                });
            })->orWhere('region_id', auth()->user()->member->region->id)
                ->whereHas('position', function (Builder $q){
                    $q->whereHas('level', function (Builder $q2) {
                        $q2->where('name', config('auth.level.ADMINAREA.name'))
                            ->orWhere('name', config('auth.level.DANSEK.name'));
                    });
                });
    }

    public function scopeFAdminKoperasi(Builder $query){
        return $query->whereHas('position', function (Builder $q) {
            $q->whereHas('level', function (Builder $q2) {
                $q2->where('name', config('auth.level.ADMINKOPERASI.name'));
            });
        });
    }

    public function scopeAdminApproval(Builder $query){
        return $query->role(['admin_koperasi', 'supervisor', 'pengurus_2', 'pengurus_1']);
    }

    public function scopeMemberApproval(Builder $query){
        return $query->whereHas('member', function (Builder $q) {
           $q->where('region_id', auth()->user()->member->region_id);
        })->role(['admin_area']);
    }

    public function scopeKaryawanPengelolaApproval(Builder $query, $user){
        return $query->role(['supervisor', 'pengurus_2', 'pengurus_1']);
    }

    public function scopeDirekturApproval(Builder $query, $user){
        return $query->role(['supervisor', 'pengurus_2', 'pengurus_1']);
    }

    public function scopeAdminKoperasiApproval(Builder $query){
        return $query->role(['admin_koperasi']);
    }

    public function scopeMemberPenjamin(Builder $query){
        return $query->where('region_id', auth()->user()->member->region_id)->role(['dansek']);
    }

    public function scopeDansekPenjamin(Builder $query){
        return $query->whereHas('member', function (Builder $q) {
            $q->where('region_id', auth()->user()->member->region_id);
        })->role(['manager']);
    }

    // public function scopeKaryawanPengelolaPenjamin(Builder $query){
    //     return $query->whereHas('member', function (Builder $q) {
    //         $q->where('region_id', auth()->user()->member->region_id);
    //     })->role(['manager']);
    // }

    public function scopeManagerPenjamin(Builder $query){
        return $query->role(['general_manager']);
    }

    public function scopeDirekturPenjamin(Builder $query){
        return $query->whereHas('member')->role(['pengawas_3', 'pengawas_2', 'pengawas_1']);
    }

    public function scopeKaryawanKoperasiPenjamin(Builder $query){
        return $query->whereHas('member')->role(['karyawan_koperasi']);
    }

    public function scopeGeneralManagerPenjamin(Builder $query){
        return $query->whereHas('member')->role(['direktur', 'direktur_utama', 'pengawas_3', 'pengawas_2', 'pengawas_1']);
    }

    public function scopePengurusPenjamin(Builder $query){
        return $query->whereHas('member')->role(['pengawas_3', 'pengawas_2', 'pengawas_1']);
    }

	public function scopeSuperVisorPenjamin(Builder $query){
        return $query->whereHas('member')->role(['pengurus_1', 'pengurus_2']);
    }

	public function scopePengelolaAreaPenjamin(Builder $query){
        return $query->whereHas('member')->role(['manager']);
    }

	public function scopeAdminAreaPenjamin(Builder $query){
        return $query->whereHas('member')->role(['manager','general_manager']);
    }

	public function scopeKaryawanPengelolaPenjamin(Builder $query){
        return $query->whereHas('member')->role(['manager']);
    }

	public function isSu()
	{
		return $this->position->level->name == config('auth.level.SUPERADMIN.name') || $this->isPow();
	}

	public function isAdminArea()
	{
		return $this->position->level->name === config('auth.level.ADMINAREA.name');
	}

	public function isHo()
	{
		return $this->position->level->name === config('auth.level.HEADOFAREA.name');
	}

	public function isAdminKoperasi()
	{
		return $this->position->level->name === config('auth.level.ADMINKOPERASI.name');
	}

	public function isSuperVisor()
	{
		return $this->position->level->name === config('auth.level.SUPERVISOR.name');
	}

	public function isPengurusSatu()
	{
		return $this->position->level->name === config('auth.level.PENGURUS1.name');
	}

	public function isPengurusDua()
	{
		return $this->position->level->name === config('auth.level.PENGURUS2.name');
	}

    public function isPendiri()
    {
        return $this->position->level->name === config('auth.level.PENDIRI.name');
    }

    public function isKaryawanPengelola()
    {
        return $this->position->level->name === config('auth.level.KARYAWANPENGELOLA.name');
    }

    public function isKaryawanKoperasi()
    {
        return $this->position->level->name === config('auth.level.KARYAWANKOPERASI.name');
    }

    public function isPengelolaArea()
    {
        return $this->position->level->name === config('auth.level.PENGELOLAAREA.name');
    }

    public function isKomisaris()
    {
        return $this->position->level->name === config('auth.level.KOMISARIS.name');
    }

    public function isHrd()
    {
        return $this->position->level->name === config('auth.level.HRD.name');
    }

	public function isPow()
    {
        return $this->position->level->name === config('auth.level.POWERADMIN.name');
    }

    /**
     * UNSURE
     * @return bool
     */
	public function isMemberVerified()
	{
        return $this->isMember() && $this->member->isVerified();
	}

	public function scopeFDansekOnly($q)
    {
        return $q->whereHas('position', function ($query){
           $query->fDansekOnly();
        });
    }

	public function isDansek()
    {
        return $this->isRegistered() && $this->position->level->name === config('auth.level.DANSEK.name');
    }

    public function isRegistered()
    {
        return $this->member()->count() > 0;
    }

    public function isMember()
    {
        return $this->isRegistered() && ($this->position->level->name === config('auth.level.ANGGOTA.name') || $this->isDansek());
    }

    public function scopeFApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    public function isApproved()
    {
        return !is_null($this->approved_at);
    }

    public function is_staff()
    {
        if(!$this->isMember() && !$this->isSu() && !$this->isPow()){
        	if($this->member->project_id  == '' || $this->member->region_id == '' || $this->member->branch_id == ''){
                return false;
        	}
        }
        return true;
    }

    public function getMember(){
	    $region = $this->region;
	    if(!empty($region)){
	        return Member::where('region_id', $region['id'])->get();
        }

	    return Member::get();
    }

    public function getUserPermissions(): Collection
    {
        $permissions = $this->permissions;

        if ($this->roles) {
            foreach ($this->roles as $role) {
                $permissions = $permissions->merge($role->permissions);
            }
        }

        return $permissions->sort()->values();
    }

    public static function authTokenFormat($token, $os_token)
    {
        $user = auth()->user();
        $user->os_token = $os_token;
        $user->update();
//        $permissions = $user->getAllPermissions()->map(function ($row) {
//            return $row->name;
//        });
        $permissions = $user->getUserPermissions()->pluck('name')->toArray();
        return [
            'access_token' => $token,
            'email' => $user->email,
            'role_level' => self::resolveRolevel(),
            'token_type' => 'bearer',
            'error' => false,
            'error_message' => '',
            'expires_in' => config('jwt.ttl'),
            'permission' => $permissions,
            'id' => $user->id,
            'name' => $user->name
        ];
    }

    public static function resolveRolevel($user = null)
    {
        if ($user === null) {
            $user = auth()->user();
        }
        return $user->position()->with('level:id,name')->get()->map(function($val){
            return strtoupper($val->level->name . '.' . $val->name);
        });
    }

    public function approval(){
        return $this->belongsTo(Approval::class, 'id', 'user_id');
    }

    public function scopeAdminArea(Builder $query){
        return $query->whereHas('member')->role(['admin_area']);
    }

    public function newContactNotification($user, $pesan)
    {
        $user->notify(new NewContactNotification($pesan));
    }

    public function tokenExpired()
    {
        \JWTAuth::check();
        if (auth()->check() !== null) {
            return true;
        }
        return false;
    }

}
