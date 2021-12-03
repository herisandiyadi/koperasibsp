<?php

namespace App\Http\Controllers;

use App\ChangeDeposit;
use App\Deposit;
use App\Helpers\cutOff;
use App\Member;
use App\PencairanSimpanan;
use App\Resign;
use App\TsDeposits;
use App\TsDepositsDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use App\ConfigDepositMembers;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deposits = Deposit::get();
        return view('deposits.deposit-type-list', compact('deposits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('deposits.deposit-type-new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'deposit_name' => 'required|unique:ms_deposits|min:3',
            'deposit_minimal' => 'required|numeric|lt:deposit_maximal',
            'deposit_maximal' => 'required|numeric|gt:deposit_minimal',
        ]);
        $newDepositType = Deposit::create($validatedData);
        session()->flash('success', trans('response-message.success.create', ['object'=>$newDepositType->deposit_name]));
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deposit = Deposit::findOrFail($id);
        return view('deposits.deposit-type-edit', compact('deposit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'deposit_name' => ['required','min:3',  Rule::unique('ms_deposits')->ignore($id)],
            'deposit_minimal' => 'required|numeric|lt:deposit_maximal',
            'deposit_maximal' => 'required|numeric|gt:deposit_minimal',
        ]);
        $deposit = Deposit::findOrFail($id);
        $deposit->update($validatedData);
        session()->flash('success', trans('response-message.success.update', ['object'=>$deposit->deposit_name]));
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deposit = Deposit::findOrFail($id);
        if($deposit->has('transaction')->count() > 0){
            session()->flash('error', trans('response-message.failed.delete', ['object'=>$deposit->deposit_name]));
        }else{
            session()->flash('success', trans('response-message.success.delete', ['object'=>$deposit->deposit_name]));
            $deposit->delete();
        }
        return $this->index();
    }

    public function pengambilan_simpanan(){
    	return view('report.deposit.pengambilan-simpanan');
	}

	public function list_pengambilan_simpanan($query){

		if($query == 'all')
		{
//			$query = PencairanSimpanan::with('member')->get();
            $query = PencairanSimpanan::getPencairanSimpananArea(auth()->user()->region);
		}
		return \DataTables::of($query)
			->editColumn('nik', function($pencairan){
				return $pencairan->member['nik'];
			})
			->editColumn('name', function($pencairan){
				return $pencairan->member['first_name'];
			})
			->editColumn('proyek', function($pencairan){
				return $pencairan->member->project['project_name'];
			})
			->editColumn('jumlah', function($pencairan){

				return number_format($pencairan->jumlah);
			})
			->editColumn('jumlah_sukarela', function($pencairan){
				$totalSukarela = TsDeposits::totalDepositSukarela($pencairan->member->id);
				return number_format($totalSukarela);
			})
			->editColumn('status', function($pencairan){

				return $pencairan->approval;
			})
			->addColumn('action', function($pencairan){
				$btnResign = '<a  class="btn btn-primary btn-sm btnEdit" onclick="showRecord('."'".$pencairan->id."'".','."'". csrf_token() ."'".')"><i class="fa fa-edit"></i></a>';

				return $btnResign;

			})->make(true);
	}

	public function getStatus(Request $request){


		$input           = Input::all();
		$idRecord        = $input['id'];
		$selected        = PencairanSimpanan::findOrFail($idRecord);
		$member = $selected->member;
		$member = Member::find($member->id);
		$bank = $member->bank;
//		return $sukarela = collect($member->depositSukarela);
		if($selected){
			$data        = array(
				'error'    => 0,
				'msg'      => 'Berhasil.',
				'json'     => $selected,
				'transfer' => $bank[0]['bank_name'] .' - an/ '. $bank[0]['bank_account_name'] .' ('.$bank[0]['bank_account_number'] .')'
			);
		} else{
			$data        = array(
				'error' => 1,
				'msg'   => 'Data pencairan tidak ditemukan.',
			);
		}
		return response()->json($data);

	}

	public function approve(Request $request){

		$approval = $request->status;
		$note = $request->note;
		$id = $request->id;
        $global = new GlobalController();
		$pencairan = PencairanSimpanan::find($id);
		if(!empty($pencairan)) {
			$pencairan->approval = $approval;
			$pencairan->reason = $note;
			$pencairan->update();

            $pokok = new TsDeposits();
            $pokok->member_id = $pencairan->member['id'];
            $pokok->deposit_number = $global->getDepositNumber();
            $pokok->ms_deposit_id = 3;
            $pokok->total_deposit = $pencairan->jumlah;
            $pokok->post_date = now()->format('Y-m-d');
            $pokok->desc = $pencairan->reason;
            $pokok->save();

            $pokok_detail = new TsDepositsDetail();
            $pokok_detail->transaction_id = $pokok->id;
            $pokok_detail->deposits_type = 'sukarela';
            $pokok_detail->debit = 0;
            $pokok_detail->credit = $pencairan->jumlah;
            $pokok_detail->total = $pencairan->jumlah;
            $pokok_detail->status = 'unpaid';
            $pokok_detail->payment_date = cutOff::getCutoff();
            $pokok_detail->save();

			$data = array(
				'error' => 0,
				'msg'   => 'Berhasil diupdate.',
			);

		}else{
			$data = array(
				'error' => 0,
				'msg'   => 'Gagal diupdate.',
			);
		}
		return response()->json($data);
	}

	public function change_deposit(){
		return view('report.deposit.perubahan-simpanan');
	}

	public function list_change_deposit($query){
		if($query == 'all')
		{
			$query = ChangeDeposit::with('member')->get();
		}

		return \DataTables::of($query)
			->editColumn('nik', function($pencairan){
				return $pencairan->member['nik'];
			})
			->editColumn('name', function($pencairan){
				return $pencairan->member['first_name'];
			})
			->editColumn('proyek', function($pencairan){
				return $pencairan->member->project['project_name'];
			})
			->editColumn('last_wajib', function($pencairan){

				return number_format($pencairan->last_wajib);
			})
			->editColumn('last_wajib', function($pencairan){

				return number_format($pencairan->last_wajib);
			})
			->editColumn('new_wajib', function($pencairan){

				return number_format($pencairan->new_wajib);
			})
			->editColumn('approval', function($pencairan){

				if($pencairan->approval){
					return 'Approve';
				}
				return 'Not Approve';
			})
			->addColumn('action', function($pencairan){
				$btnResign = '<a  class="btn btn-primary btn-sm btnEdit" onclick="showRecord('."'".$pencairan->id."'".','."'". csrf_token() ."'".')"><i class="fa fa-edit"></i></a>';
				return $btnResign;

			})->make(true);
	}

	public function getPerubahanSimpanan(Request $request){


		$input           = Input::all();
		$idRecord        = $input['id'];
		$selected        = ChangeDeposit::findOrFail($idRecord);
		$member = $selected->member;
		$member = Member::find($member->id);
		$bank = $member->bank;
//		return $sukarela = collect($member->depositSukarela);
		if($selected){
			$data        = array(
				'error'    => 0,
				'msg'      => 'Berhasil.',
				'json'     => $selected,
				'transfer' => $bank[0]['bank_name'] .' - an/ '. $bank[0]['bank_account_name'] .' ('.$bank[0]['bank_account_number'] .')'
			);
		} else{
			$data        = array(
				'error' => 1,
				'msg'   => 'Data pencairan tidak ditemukan.',
			);
		}
		return response()->json($data);

	}

	public function change_deposit_approve(Request $request){

		$approval = $request->status;
		$note = $request->note;
		$id = $request->id;

		$pencairan = ChangeDeposit::find($id);
		if(!empty($pencairan) && $approval != 0) {

			$depositWajib = ConfigDepositMembers::where('member_id', $pencairan->member_id)
			->where('type', 'wajib')->first();
			$depositWajib->value = $pencairan->new_wajib;
			$depositWajib->save();

			$depositSukarela = ConfigDepositMembers::where('member_id', $pencairan->member_id)
			->where('type', 'sukarela')->first();
			$depositSukarela->value = $pencairan->new_sukarela;
			$depositSukarela->save();


			$pencairan->approval = $approval;
			$pencairan->update();

			$data = array(
				'error' => 0,
				'msg'   => 'Berhasil diupdate.',
			);

		}else{
			$data = array(
				'error' => 0,
				'msg'   => 'Gagal diupdate.',
			);
		}
		return response()->json($data);
	}
}
