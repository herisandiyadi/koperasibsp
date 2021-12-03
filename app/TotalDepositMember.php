<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TotalDepositMember extends Model
{
    protected $table = 'total_deposit_member';

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function ms_deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
    public function totalDeposit($id){
        return $this->where(['member_id' => $id])->sum('value');
    }

    public static function getDepositArea($region = null){
        $selected = self::all();
        if($region != null){
            $selected = self::whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function getDepositAreaPokok($region = null){
        $selected = self::where('ms_deposit_id', 1)->get();
        if(!empty($region)){
            $selected = self::where('ms_deposit_id', 1)->whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function getDepositAreaWajib($region = null){
        $selected = self::where('ms_deposit_id', 2)->get();
        if(!empty($region)){
            $selected = self::where('ms_deposit_id', 2)->whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function getDepositAreaSukarela($region = null){
        $selected = self::where('ms_deposit_id', 3)->get();
        if(!empty($region)){
            $selected = self::where('ms_deposit_id', 3)->whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function getDepositAreaBerjangka($region = null){
        $selected = self::where('ms_deposit_id', 4)->get();
        if(!empty($region)){
            $selected = self::where('ms_deposit_id', 4)->whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function getDepositAreaShu($region = null){
        $selected = self::where('ms_deposit_id',5)->get();
        if(!empty($region)){
            $selected = self::where('ms_deposit_id',5)->whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function getDepositAreaLainnya($region = null){
        $selected = self::where('ms_deposit_id',6)->get();
        if(!empty($region)){
            $selected = self::where('ms_deposit_id',6)->whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function totalDepositPokok($id){
        return self::where([
            'member_id' => $id,
            'ms_deposit_id' => 1
        ])->sum('value');
    }

    public static function totalDepositWajib($id){
        return self::where([
            'member_id' => $id,
            'ms_deposit_id' => 2
        ])->sum('value');
    }

    public static function totalDepositSukarela($id){
        return self::where([
            'member_id' => $id,
            'ms_deposit_id' => 3,
        ])->sum('value');
    }

    public static function totalDepositBerjangka($id){
        return self::where([
            'member_id' => $id,
            'ms_deposit_id' => 4
        ])->sum('value');
    }

    public static function totalDepositShu($id){
        return self::where([
            'member_id' => $id,
            'ms_deposit_id' => 5
        ])->sum('value');
    }

    public static function totalDepositLainnya($id){
        return self::where([
            'member_id' => $id,
            'ms_deposit_id' => 6
        ])->sum('value');
    }

    public static function getTopDepositArea($region = null){
        $selected = self::with('member')->whereHas('member')
            ->select('member_id', DB::raw('sum(value) as total'))
            ->groupBy('member_id')->orderBy('total', 'DESC')
            ->limit(8);
        if(!empty($region)){
            $selected = self::with('member')->whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->select('member_id', DB::raw('sum(value) as total'))
                ->groupBy('member_id')->orderBy('total', 'DESC')
                ->limit(8);
        }


        return $selected;
    }
}
