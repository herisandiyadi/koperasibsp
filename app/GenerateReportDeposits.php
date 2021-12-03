<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenerateReportDeposits extends Model
{
    //

    protected $table = 'generate_report_deposits';
    protected $fillable = ['name','start','end','status','attachment'];
}
