<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $table = 'ms_projects';
//    protected $dates = ['start_date','end_date'];
    protected $fillable = ['region_id','region_code','project_name','address','start_date','end_date','contract_number','date_salary','total_member','status'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id','id');
    }

    public function setRegionCodeAttribute($value)
    {
        if($this->region()->count() > 0)
        {
            $regionCode = $this->region->code;
        }else{
            $regionCode = null;
        }
        return $this->attributes['region_code'] = $regionCode;
    }

    public function scopeFByName($q, $name)
    {
        return $q->where('project_name', 'like', '%' . $name . '%');
    }
    public function locations()
    {
        return $this->hasMany(Location::class);
	}

	public static function getProjectArea($region){
//        $selected = self::where([['start_date', '<=', Carbon::now()->format('Y-m-d')], ['end_date', '>=', Carbon::now()->format('Y-m-d')]])->count();
        $selected = self::where('status', 'aktif')->count();

        if(!empty($region)){
//            $selected = self::whereHas('region', function ($query) {
//                return $query->where('region_id', '=', auth()->user()->region['id']);
//            })->where([['start_date', '<=', Carbon::now()->format('Y-m-d')], ['end_date', '>=', Carbon::now()->format('Y-m-d')]])->count();

            $selected = self::whereHas('region', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->where('status', 'aktif')->count();
        }


        return $selected;
    }

	
}
