<?php

namespace App\Http\Controllers;

use App\Deposit;
use App\Helpers\dateConvert;
use App\Helpers\grafikData;
use App\Loan;
use App\Resign;
use App\TotalDepositMember;
use App\TsDeposits;
use DB;
use Auth;
use App\user;
use App\Member;
use App\TsLoans;
use App\Region;
use App\Project;
use App\Position;
use Carbon\Carbon;
use App\MemberPlafon;
use App\TsLoansDetail;
use App\DepositTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends GlobalController
{
	function __construct()
	{
        $this->currMonth   = Carbon::now()->format('m');
		$this->date        = Carbon::now()->format('Y-m-d');
	}
    public function index()
    {
//        dd(auth()->user()->can('view.account.register'));
        $startlasteYear = now()->subYear(1)->firstOfYear()->format('Y-m-d');
        $endlastYear = now()->subYear(1)->lastOfYear()->format('Y-m-d');
        $start_thisYear = now()->firstOfYear()->format('Y-m-d');
        $end_thisYear = now()->lastOfYear()->format('Y-m-d');
        if (!auth()->user()->isMember()) {
            $data['allMember'] = Member::getMemberArea(auth()->user()->region)->count();
            $data['newMember'] = Member::getMemberArea(auth()->user()->region)->where('created_at','LIKE', '%-'.$this->currMonth.'%')->count();
            $data['getProj']   = Project::getProjectArea(auth()->user()->region);
            $data['tsDeposit'] = TsDeposits::getDepositArea(auth()->user()->region);
            $data['countM']    = Member::getMemberAreaCount(auth()->user()->region)->select([DB::raw('DATE_FORMAT(join_date, "%Y-%b") as month, count(*) as counter')])
                                 ->orderBy(DB::raw('month'), 'desc')
                                 ->groupBy(DB::raw('month'))
                                 ->take(12)
                                 ->get();
            $data['countMResign']    = Resign::getMemberAreaCount(auth()->user()->region)->select([DB::raw('DATE_FORMAT(date, "%Y-%b") as month, count(*) as counter')])
//                ->where('is_active', 0)
                ->orderBy(DB::raw('month'), 'desc')
                ->groupBy(DB::raw('month'))
                ->take(12)
                ->get();
            $data['first']     = Member::orderBy('created_at')->first();
            if ($data['first']) {
                $data['first'] = Carbon::parse($data['first']->created_at);
            } else {
                $data['first'] = Carbon::today();
            }
            $data['topPinjaman'] = TsLoans::getTopPinjamanArea(auth()->user()->region)->get();
            $data['topPeminjam'] = TsLoans::getTopPeminjamArea(auth()->user()->region)->get();
            $data['topSimpanan'] = TsDeposits::getTopDepositArea(auth()->user()->region)->get();
            $data['grafikLastYear'] = grafikData::simpananYearly($startlasteYear, $endlastYear, auth()->user()->region);
            $data['grafikThisYear'] = grafikData::simpananYearly($start_thisYear, $end_thisYear, auth()->user()->region);
        } else {
            $ts = TsLoans::leftJoin('ts_loan_details', function($join) {
                     $join->on('ts_loans.id', '=', 'ts_loan_details.loan_id');
                 })
                 ->where('ts_loans.member_id', Auth::user()->member->id)
                ->where('ts_loan_details.approval', 'belum lunas')
                 ->get(['ts_loan_details.value']);
            $sum = 0;
            foreach ($ts as $el) {
                $sum += $el->value;
            }
            $data['getProj']   = '';

            if(auth()->user()->member->hasProject())
            {
                $data['getProj']   = (Member::where('id', Auth::user()->member->id)->first())->project->project_name;
            }

            $data['tsLoan']    = $sum;
            $data['plafon']    = MemberPlafon::where('member_id', Auth::user()->member->id)->sum('nominal');
            $data['tsDeposit'] = TsDeposits::getDepositMember(Auth::user()->member->id);
            $data['countDep']  = DepositTransaction::select([DB::raw('DATE_FORMAT(created_at, "%b-%Y") as month, sum(total_deposit) as deposit')])->orderBy(DB::raw('month'), 'desc')
                                 ->groupBy(DB::raw('month'))
                                 ->where('status', 'paid')->get();
        }
        return view('dashboards.main', $data);
    }
    public function memberActive()
    {
        $selected = Member::getMemberAreaCount(auth()->user()->region)->whereIsActive(1)->get();
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('fullname', function ($selected) {
                return $selected->first_name .' '.$selected->last_name;
            })
            ->editColumn('project', function ($selected) {
                if($selected->hasProject())
                {
                    return $selected->project->project_name;
                }
               return '';
            })
            ->addColumn('action',function($selected){
                return
                '<center>
                <a  class="btn btn-info btn-sm btnEdit" href="/profile-member/'.Crypt::encrypt($selected->user_id).'"  data-toggle="tooltip" title="Cek data"><i class="ion ion-aperture"></i></a>
                </a>
                </center>';
            })
            ->make(true);
        }
    }
    public function profileMember($el='')
    {
        $decrypter = Crypt::decrypt($el);
        $getData   = User::leftJoin('ms_members', function($join) {
                         $join->on('ms_members.user_id', '=', 'users.id');
                     })
                     ->leftJoin('ms_banks', function($join) {
                         $join->on('ms_members.id', '=', 'ms_banks.member_id');
                     })
                     ->leftJoin('positions', function($join) {
                         $join->on('ms_members.position_id', '=', 'positions.id');
                     })
                    ->where('users.id', $decrypter)
                    ->first();
        $region    = Region::all();
        $pst       = Position::fMemberOnly()->get();
        $spcMember = Member::where('user_id', $decrypter)->first();
        return view('dashboards.profile-member', compact('getData', 'region', 'spcMember', 'pst'));
    }

    public function myProfile($el='')
    {
        $user = auth()->user();
        $getData   = User::leftJoin('ms_members', function($join) {
            $join->on('ms_members.user_id', '=', 'users.id');
        })
            ->leftJoin('ms_banks', function($join) {
                $join->on('ms_members.id', '=', 'ms_banks.member_id');
            })
            ->leftJoin('positions', function($join) {
                $join->on('ms_members.position_id', '=', 'positions.id');
            })
            ->where('users.id', $user->id)
            ->first();
        $region    = Region::all();
        $pst       = Position::fMemberOnly()->get();
        $spcMember = Member::where('user_id', $user->id)->first();
        return view('dashboards.my-profile', compact('getData', 'region', 'spcMember', 'pst'));
    }

    public function countMember()
    {
        return Member::getMemberArea(auth()->user()->region)->count();
    }
}
