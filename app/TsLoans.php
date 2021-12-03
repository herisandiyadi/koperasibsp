<?php

namespace App;

use App\Notifications\NewLoanNotification;
use Approval\Models\Approval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use NotificationChannels\OneSignal\OneSignalChannel;

class TsLoans extends Model
{
	protected $table='ts_loans';
    protected $casts = [
        'approvemans' => 'json',
        'notes' => 'json',
    ];


    /**
     * @param null $approvals
     * @param string $status
     * @param string $note
     * @param bool $is_approve
     * @param bool $is_revision
     * @param bool $is_reject
     * @param bool $is_waiting
     * @return array
     */
    public function generateApprovalsLoan($approvals = null, $status = 'menunggu', $note = '', $is_approve = false, $is_revision = false, $is_reject = false, $is_waiting = false)
    {
        $dataApproval = [];
        foreach ($approvals as $approveman) {
            $dataApproval['fk'] = $this->id;
            $dataApproval['model'] = 'App\TsLoans';
            $dataApproval['is_approve'] = $is_approve;
            $dataApproval['is_revision'] = $is_revision;
            $dataApproval['id_reject'] = $is_reject;
            $dataApproval['is_waiting'] = $is_waiting;
            $dataApproval['is_approve'] = $is_approve;
            $dataApproval['status'] = $status;
            $dataApproval['note'] = $note;
            $approveman['status'] = $status;
            $approval = new Approveman($approveman->toArray());
            $dataApproval['approval'] = $approval;
            $dataApproval['user_id'] = $approval->id;
            Approvals::create($dataApproval);
            $this->newLoanBlastTo($approvals, ['database', OneSignalChannel::class]);

        }
        return $dataApproval;
    }

    public function approval(){
        return $this->belongsTo(Approval::class, 'id', 'fk');
    }

	public function detail()
    {
        return $this->hasMany(TsLoansDetail::class, 'loan_id', 'id');
	}

	public function member()
	{
		return $this->belongsTo(Member::class, 'member_id');
	}

	public function ms_loans()
	{
		return $this->belongsTo(Loan::class, 'loan_id');
	}

	static function totalLoans($id){
       return TsLoans::where(['approval'=>'belum lunas','approval'=>'lunas','member_id' => $id])->sum('value');
	}

	public function getJasa()
	{

		return $this->value * ($this->rate_of_interest / 100);
	}

	public static function getLoanArea($region){
        $selected = self::all();
	    if(!empty($region)){
            $selected = self::whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }

    public static function getTopPinjamanArea($region){
        $selected = self::whereHas('ms_loans')->with('ms_loans')
            ->select('loan_id', DB::raw('sum(value) as total'), DB::raw('count(id) as total_user'))
            ->groupBy('loan_id')->orderBy('total', 'DESC')
            ->limit(5);

        if(!empty($region)){
            $selected = self::whereHas('ms_loans')->whereHas('member', function ($query) {
                $query->where('region_id', '=', auth()->user()->region['id']);
                })
                ->with('ms_loans')
                ->select('loan_id', DB::raw('sum(value) as total'), DB::raw('count(id) as total_user'))
                ->groupBy('loan_id')->orderBy('total', 'DESC')
                ->limit(5);
        }

        return $selected;
    }

    public static function getTopPeminjamArea($region){
        $selected = self::whereHas('ms_loans')->with('member')
            ->select('member_id', DB::raw('sum(value) as total'), DB::raw('count(member_id) as total_pinjaman'))
            ->groupBy('member_id')->orderBy('total', 'DESC')
            ->limit(5);


        if(!empty($region)){
            $selected = self::whereHas('ms_loans')->whereHas('member', function ($query) {
                $query->where('region_id', '=', auth()->user()->region['id']);
            })
                ->select('member_id', DB::raw('sum(value) as total'), DB::raw('count(member_id) as total_pinjaman'))
                ->groupBy('member_id')->orderBy('total', 'DESC')
                ->limit(5);
        }

        return $selected;
    }

    public function newLoanBlastTo($users, $via = [OneSignalChannel::class])
    {
        foreach ($users as $user)
        {
            $user->notify(new NewLoanNotification($this, $via));
        }
    }
}
