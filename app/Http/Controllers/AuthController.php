<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

use Session;

class AuthController extends Controller
{
    public function login()
    {
        if(Session::get('isLogin')) {
            return Redirect::to('/views/home');
        }

        return view('Page.Login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $checUser = User::where('email', '=', $request->email)
        ->orWhere('nip', '=', $request->email)
        ->first();

        if(!$checUser) {
            return [
                "status" => "error",
                "title" => "Kesalahan!",
                "message" => "Data karyawan tidak ada"
            ];
        }

        $checkPassword = Hash::check($request->password, $checUser->password);
        
        if(!$checkPassword) {
            return [
                "status" => "error",
                "title" => "Kesalahan!",
                "message" => "Password tidak benar"
            ];
        }

        Session::put('isLogin', true);
        Session::put('id', $checUser->id);
        Session::put('level', $checUser->level);
        Session::put('nama', $checUser->nama);

        return ['status' => "success"];
    }

    public function logout()
    {
        Session::flush();
        return Redirect::to('/');
    }
}
