<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenerateReportMembers extends Model
{
    protected $table = 'generate_report_members';
    protected $fillable = ['name','start','end','status','attachment'];
}
