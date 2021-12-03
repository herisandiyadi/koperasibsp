<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Position;
use App\Region;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \JavaScript::put([
            'user' => User::first()
		]);
		$users = User::fMemberOnly(false);
		if(auth()->user()->isPow())
		{
			$users = $users->get();
		}else{
			$users = $users->fNoSuper()->fNoPower()->get();
		}
        return view('users.user-list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->isPow())
        {
            # dummy
            $positions = Position::whereNotNull('id');
        }else{
            $positions = Position::fNoSuper();
        }
        $regions = Region::get();
        $positions = $positions->fMemberOnly(false)->pluck('name','id')->toArray();
        return view('users.user-new', compact('positions','regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewUserRequest $request)
    {
//        Validator::make($data, $rules, $messages);
        $newRole = Position::where('id', $request->position_id)->first();
        if(!auth()->user()->isPow() && $newRole->level->name === config('auth.level.SUPERADMIN.name'))
        {
            session()->flash('warning', trans('response-message.unauthorized.visit',['object'=>'User']));
            return redirect()->back();
        }
        $validated = $request->validated();
        $validated['password'] = \Hash::make($validated['password']);
        $user = User::create($validated);
        $user->assignRole($newRole->level->name);
        session()->flash('success', trans('response-message.success.create',['object'=>'User']));
        return redirect('users');
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
        $user = User::findOrFail($id);
        if(!auth()->user()->isPow() && $user->position->level->isSuper())
        {
            session()->flash('warning', trans('response-message.unauthorized.visit',['object'=>'User']));
            return redirect()->back();
		}
		if(auth()->user()->isPow())
		{
			$positions = Position::fNoPower()->pluck('name','id')->toArray();
		}else{
			$positions = Position::fNoSuper()->fNoPower()->pluck('name','id')->toArray();
		}

		$regions = Region::get()->pluck('name_area', 'id');

        return view('users.user-edit', compact('user','positions', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();
        //remove role
        $role = $user->position->level->name;
        if(!auth()->user()->isPow() && $user->position->level->isSuper())
        {
            session()->flash('warning', trans('response-message.unauthorized.visit',['object'=>'User']));
            return redirect()->back();
		}
        $user->removeRole($role);
        $user->update([
            'name'=> $request->name,
            'position_id'=> $request->position_id,
            'username'=> $request->username,
            'email'=> $request->email,
        ]);

        // change user role
        $newRole = Position::where('id', $request->position_id)->first();
        $user->assignRole($newRole->level->name);
       if(!empty($validated['old_password'])){
            $validatedData = $request->validate([
               'password' => 'required|min:6|confirmed'
            ]);
            if(\Hash::check($request->old_password ,$user->password)) {
                $user->update([
                   'password'=> \Hash::make($request->password)
                ]);
            }else{
               $request->session()->flash('errors', collect(['Password yang anda masukkan salah']));
               return redirect()->back()->withInput();
            }
       }
        $request->session()->flash('success', trans('response-message.success.update',['object'=>'User']));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if($user->position->level->name === config('auth.level.SUPERADMIN.name'))
        {
            session()->flash('warning', trans('response-message.unauthorized.visit',['object'=>'User']));
            return redirect()->back();
        }
        $user->delete();
        session()->flash('success', trans('response-message.success.delete', ['object'=>'User']));
        return redirect()->back();
    }

    public function markAsRead(Request $request, $id)
    {
        $notif = auth()->user()->unreadNotifications()->where('id', $id);
        if($notif->count() > 0)
        {
            $notif->first()->markAsRead();
        }
        return redirect()->back();
    }

    public function viewAllNotifications(Request $request)
    {
        $notifications = auth()->user()->notifications;
        return view('users.notifications.notification-list', compact('notifications'));
    }

    public function getAllNotifications()
    {
        $notifications = auth()->user()->notifications;
        return \DataTables::of($notifications)
            ->addColumn('title', function($notification){
                return $notification->data['content']['title'];
            })
            ->addColumn('description', function($notification){
                return $notification->data['content']['description'];
            })
            ->addColumn('date', function($notification){
                return $notification->created_at->toDateTimeString();
            })
            ->addColumn('action', function($notification){
                $urlResolver = url("notification/".$notification->id."/resolve");
                $btnResolver= '<a class="btn" href="'.$urlResolver.'" data-toggle="tooltip" title="Kunjungi"><i class="fa fa-link"></i></a>';
                if($notification->read_at === null)
                {
                    $btnRead = '<a class="btn" href="'.url("notification/".$notification->id."/mark-as-read").'" data-toggle="tooltip" title="Tandai sebagai sudah dibaca"><i class="fa fa-envelope-o"></i></a>';
                }else{
                    $btnRead= '<button class="btn" data-toggle="tooltip" title="Dibaca '.$notification->read_at->toDateTimeString().'"><i class="fa fa-envelope-open-o"></i></button>';
                }
                return $btnResolver.$btnRead;
            })
            ->make();
    }

    public function resolveNotifUrl(Request $request, $id)
    {
        $notif = auth()->user()->notifications()->where('id', $id);
        if($notif->count() > 0)
        {
            $notif = $notif->first();
            $obj = $notif->data['content']['object_type']::whereId($notif->first()->data['content']['object']['id'])->first();
            switch ($notif->data['url']){
                case'profile-member';
                    $url = $notif->data['url'].'/'.encrypt($obj->id);
                    break;
                case'member';
                    $url = 'profile-member/'.encrypt($obj->id);
                    break;
                case 'loan';
                    $url = 'loan-detail/'.encrypt($obj->id);
                    break;
				case 'resign';
					$url = 'list-retrieve-deposit';
					break;
				case 'change-deposit';
					$url = 'list-change-deposit';
					break;
                default:
                    $url = $notif->data['url'];
                    break;
            }
            if(is_null($notif->read_at)){
                $notif->markAsRead();
            }
            return redirect($url);
        }
        return redirect('notification/view-all');
    }
}
