<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Level;

class Position extends Model
{
    use SoftDeletes, HasRoles;
    protected $table='positions';
    protected $guard_name = 'web';
    protected $fillable = ['level_id','name','description','order_level'];

    public function member()
    {
        return $this->hasMany(Member::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function scopeFNoSuper($query)
    {
        return $query->whereHas('level', function($q){
            $q->fNoSuper();
        });
    }

    public function scopeFNoPower($query)
    {
        return $query->whereHas('level', function($q){
            $q->fNoPower();
        });
    }

    public function scopeFAdminKoperasi($query)
    {
        return $query->whereHas('level', function($q){
            $q->FAdminKoperasi();
        });
    }

    public function scopeFAdmin($query)
    {
        return $query->whereHas('level', function($q){
            $q->FAdmin();
        });
    }

    public function scopeFAdminPow($query)
    {
        return $query->whereHas('level', function($q){
            $q->FAdminPow();
        });
    }

    public function scopeFAdminRegister($query)
    {
        return $query->whereHas('level', function($q){
            $q->FAdminRegister();
        });
    }

    public function scopeFHasPrivilege($q)
    {
        return $q->has('privilege');
	}

	public function scopeFMemberOnly($query, $true = true)
	{
		return $query->whereHas('level', function($q) use($true){
		    $q->fMemberOnly($true);
		});
	}

	public function scopeFDansekOnly($query)
    {
        return $query->whereHas('level', function($q) {
            $q->fDansekOnly();
        });
    }
}
