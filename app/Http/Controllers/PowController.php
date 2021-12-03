<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use App\Jobs\MemberDepositGenerator;
use App\Jobs\NotifyNewLoanApplication;
use App\Jobs\NotifyNewMemberRegisteredJob;
use App\Member;
use App\Notifications\LoanApplicationStatusUpdated;
use App\Notifications\NewLoanApplication;
use App\Notifications\NewMemberRegistered;
use App\Position;
use App\Project;
use App\Role;
use App\TsLoans;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Article;
use Spatie\Permission\Models\Permission;

//use Spatie\Permission\Models\Permission;

class PowController extends Controller
{
    public function permissionCreator(){
        return (int)auth()->user()->hasRole('SUPERADMIN');
       /* $arr = [
            "auth.management",
            "project.management",
            "transaction.management"
        ];

        foreach ($arr as $permit)
        {
            \Spatie\Permission\Models\Permission::create(['name' => 'view.'.$permit]);
            \Spatie\Permission\Models\Permission::create(['name' => 'create.'.$permit]);
            \Spatie\Permission\Models\Permission::create(['name' => 'update.'.$permit]);
            \Spatie\Permission\Models\Permission::create(['name' => 'delete.'.$permit]);
        }*/

    }

    public function test()
    {
//        foreach ( \Spatie\Permission\Models\Role::where('name','ADMINKOPERASI')->get() as $role) {
////            return config('auth.privileges.'.$role->name);
//            $role->syncPermissions(config('auth.privileges.'.$role->name));
//        }
//        return config('auth.privileges.SUPERADMIN');
        $super = \Spatie\Permission\Models\Role::where('name', 'PENGURUS_1')->first();
        return $super->permissions()->get(['name'])->pluck('name');
        return (int)auth()->user()->isMember();
		return Article::fByTags('Halal')->get();
//        return User::find(14)->is_staff();
//        $comrade =  Member::latest()->first()->position;
//        dd($comrade);
//        return TsLoans::first()->member->superior()->get();
//        $member = Member::first();
//        return $member->superior()->get();
//        $user = $member->user;
//        return $user->assignRole($user->position->level->name);
//        NotifyNewMemberRegisteredJob::dispatch($member)->delay(now()->addSeconds(10));
//        return User::find(14)->getAllPermissions();
        $loanApplication = TsLoans::first();
//        return $loanApplication->applicant;
//        $superiors = $loanApplication->member;
////        return (int)$superiors->user->isDansek();
//        if($superiors->count() > 0)
//        {
//            foreach ($superiors->get() as $superior)
//            {
//                $user = $superior->user;
//                $user->notify(new NewLoanApplication($loanApplication));
//            }
//        }
        $loanApplication->member->user->notify(new LoanApplicationStatusUpdated($loanApplication));
//        NotifyNewLoanApplication::dispatch($loanApplication);
        return 1;
//        return (int)auth()->user()->isMemberVerified();
//        $user = auth()->user();
//        $member = User::first();
//        return $user->notify(new NewMemberRegistered($member));
    }
}
