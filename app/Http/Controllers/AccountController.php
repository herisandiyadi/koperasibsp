<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function password(){
        $auth = auth()->user();
        return view('account.password.edit', compact('auth'));
    }

    public function updatePassword(Request $request){
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required_with:new_password|same:new_password|min:6',
        ]);

        $hashedPassword = auth()->user()->password;

        if (\Hash::check($request->old_password , $hashedPassword )) {

            if (!\Hash::check($request->new_password , $hashedPassword)) {

                $users = User::find(auth()->user()->id);
                $users->password = bcrypt($request->new_password);
                User::where( 'id' , auth()->user()->id)->update( array( 'password' =>  $users->password));
                session()->flash('success',collect(['Password Berhasil diubah']));
                return redirect()->back();
            } else{
                session()->flash('errors',collect(['Password Baru Tidak Boleh Sama dengan Password Lama']));
                return redirect()->back();
            }

        } else {
            session()->flash('errors',collect(['Password Lama yang Anda Masukan Salah']));
            return redirect()->back();
        }

    }

    public function email(){
        $auth = auth()->user();
        return view('account.email.edit', compact('auth'));
    }

    public function updateEmail(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $hashedPassword = auth()->user()->password;
        if (\Hash::check($request->password , $hashedPassword )) {

            if (!\Hash::check($request->email , auth()->user()->email)) {

                $users = User::find(auth()->user()->id);
                $users->email = $request->email;
                User::where( 'id' , auth()->user()->id)->update( array( 'email' =>  $users->email));
                session()->flash('success',collect(['Email Berhasil diubah']));
                return redirect()->back();
            } else{
                session()->flash('errors',collect(['Email Baru Tidak Boleh Sama dengan email lama']));
                return redirect()->back();
            }

        } else {
            session()->flash('errors',collect(['Email yang Anda Masukan Salah']));
            return redirect()->back();
        }

    }
}
