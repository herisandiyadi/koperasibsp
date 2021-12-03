<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class Level extends Model
{
    use SoftDeletes;
    protected $table = 'levels';
	protected $fillable = ['name', 'description'];

    public function position(){
        return $this->hasMany(Position::class);
    }

    /**
     * Return array of member level name
     * @return array
     * Author: msyahidin
     */
    public static function getMembers()
    {
        return [
            config('auth.level.ANGGOTA.name'),
            config('auth.level.DANSEK.name'),
            'MEMBER',
        ];
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function countUser()
    {
        return User::whereHas('position', function($q){
            $q->where('level_id', $this->id);
        })->count();
    }

    public function scopeFNoSuper($query)
    {
        return $query->where('name', '<>',config('auth.level.SUPERADMIN.name'));
	}

	public function scopeFNoPower($query)
	{
		return $query->where('name','<>',config('auth.level.POWERADMIN.name'));
	}

    public function scopeFAdminKoperasi($query)
    {
        return $query->where('name', config('auth.level.ADMINKOPERASI.name'));
    }

    public function scopeFAdmin($query)
    {
        return $query->where('name', config('auth.level.SUPERADMIN.name'));
    }

    public function scopeFAdminPow($query)
    {
        return $query->where('name', config('auth.level.POWERADMIN.name'));
    }

    public function scopeFAdminRegister($query){
        return $query->FAdminKoperasi()->FAdmin();
    }

	public function isSuper()
	{
		return $this->name === config('auth.level.SUPERADMIN.name');
	}

	public function isPower()
	{
		return $this->name === config('auth.level.POWERADMIN.name');
	}

    public static function privilegeGenerator()
    {
        $p = Permission::where('name','like','%account%')->where('guard_name','web')->get();
        foreach ($p as $ps)
        {
            $ps->assignRole('SUPERADMIN');
        }
    }

    /**
     * @param $query
     * @param bool $true
     * @return mixed
     * Author: msyahidin
     */
    public function scopeFMemberOnly($query, $true = true)
    {
        if($true)
        {
            return $query->whereIn('name', $this->getMembers());
        }
        return $query->whereNotIn('name', $this->getMembers());
    }

    public function scopeFDansekOnly($query)
    {
        return $query->where('name', config('auth.level.DANSEK.name'));
    }
}
