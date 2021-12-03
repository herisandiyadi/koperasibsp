<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyNewMemberRegisteredJob;
use App\Jobs\SendWelcomeMessageJob;
use App\Notifications\NewMemberRegistered;
use DB;
use App\Member;
use App\TsDeposits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class LoadController extends Controller
{
    public function loadData(Request $request) {
        $replace = str_replace('#', '', $request->find);
        $data    =  DB::table($request->table)
                  ->where($replace, $request->trigger)
                  ->get();
        return response()->json($data);
    }
    public function verification($el='')
    {
        $email                 = Crypt::decrypt($el);
        $check                 = Member::where('email', $email)->first();
        if($check->verified_at == null){
           $check->verified_at = new \DateTime;
           $check->save();
           NotifyNewMemberRegisteredJob::dispatch($check);
           SendWelcomeMessageJob::dispatch($check);
        }
        return view('auth.verified', compact('email'));
    }
    public function sendEmail($username, $email, $password)
    {
      #SEND EMAIL HERE
            $title   = "You have successfully been registered";
            $subject = $title;
            $icon    = '<h4><font style="color:#fff">Akses</font> BSP Koperasi</h4>';
            $content = '<div>
                             <p>Dear Client, </p>
                             <p>We would like to inform you that your account has successfully been registered with the following detail: </p>
                             <table>
                <tr>
                  <td><p>Username</p></td>
                  <td><p><b>: '.$username.'</b></p></td>
                </tr>
                <tr>
                  <td><p>Email</p></td>
                  <td><p><b>: '.$email.'</b></p></td>
                </tr>
                <tr>
                  <td><p>Password</p></td>
                  <td><p><b>: '.$password.'</b></p></td>
                </tr>
                             </table>
                             <br/>
                            <p style="color:#000000;font-weight:800;">IMPORTANT! This email content is private and confidential. Please verification immediately by clicking button below</p>
                            <a href="'.asset('verify/'.Crypt::encrypt($email).'/').'"><button class="btn">Click Here</button></a>
                        </div>';

        #END SEND EMAIL
         $this->email = $email;
      \Mail::send('auth.send', ['title' => $title, 'icon' => $icon, 'content' => $content], function($message){
        $message->subject('Register Member');
        $message->from('cs_admin@bspkoperasi.co.id');
        $message->to($this->email);
      });
    }
    public function sendEmailActiv($name, $email, $status)
    {
    	#SEND EMAIL HERE
      $title   = "You have successfully been activated";
      $subject = $title;
      $icon    = '<h4><font style="color:#fff">Akses</font> BSP Koperasi</h4>';
      $content = '<div>
                       <p>Dear Client, </p>
                       <p>We would like to inform you that your account has successfully been '.$status.' with the following detail: </p>
                       <table>
					<tr>
						<td><p>Full name</p></td>
						<td><p><b>: '.$name.'</b></p></td>
					</tr>
					<tr>
						<td><p>Email</p></td>
						<td><p><b>: '.$email.'</b></p></td>
					</tr>
          </table>
           <br/>
          <p style="color:#000000;font-weight:800;">IMPORTANT! This email content is private and confidential. You can immediately login by clicking button below</p>
          <a href="'.asset('/login').'"><button class="btn">Click Here</button></a>
                  </div>';

        #END SEND EMAIL
         $this->email = $email;
    	\Mail::send('auth.send', ['title' => $title, 'icon' => $icon, 'content' => $content], function($message){
        $message->subject('Register Member');
    		$message->from('cs_admin@bspkoperasi.co.id');
    		$message->to($this->email);
    	});
    }

    public function testPost(Request $request)
    {
      $selected = TsDeposits::where('id', $request->idSearch)->get();
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('member', function ($selected) {
                return $selected->member['first_name'] .' '. $selected->member['first_name'];
      })
      ->editColumn('total_deposit', function ($selected){
        return 'Rp. '. number_format($selected->total_deposit);
      })
            ->addColumn('action',function($selected){
                return
                '<center>
                <a  class="btn btn-info btn-sm btnEdit" href="/ts-deposits/'.$selected->id.'/edit"><i class="glyphicon glyphicon-pencil"></i></a>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'ts-deposits'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listLoans'".')">
                <i class="glyphicon glyphicon-trash" data-token="{{ csrf_token() }}"></i></a>
                </center>';
            })
            ->make(true);
     }
    }
}
