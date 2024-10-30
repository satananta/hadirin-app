<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\User;
use Session;

class AdminController extends Controller
{
    public function login()
    {
         if(Session::get('isLogin')) {
            return redirect('/admin/dashboard');
        }

        return view('Page.admin.login');
    }

    public function loginAction(AdminLoginRequest $request)
    {
       $validated = $request->validated();

       $checUser = User::where('email', $request->email)
       ->where('level', 'admin')
       ->orWhere('nip', $request->email)
       ->first();

        if(!$checUser) {
            return redirect('/admin')->with(['error' => 'User tidak ada']);
        }

        $checkPassword = Hash::check($request->password, $checUser->password);
        
        if(!$checkPassword) {
            return redirect('/admin')->with(['error' => "Password tidak benar"]);
        }

        Session::put('isLogin', true);
        Session::put('id', $checUser->id);
        Session::put('level', $checUser->level);

        return redirect('/admin/dashboard');
        
    }

    public function dashboard() {

        $totalPegawai = User::count();

        $totalIzin = Absen::whereIn('kategori', ['Izin', 'Cuti'])
        ->whereDay('tanggal_absen', date('d'))
        ->count();

        $totalHadir = Absen::where('status', 'Hadir')
        ->whereDay('tanggal_absen', date('d'))
        ->count();

        $totalTidakHadir = Absen::where('status', 'Tidak Hadir')
        ->whereNotIn('kategori', ['Izin', 'Cuti'])
        ->whereDay('tanggal_absen', date('d'))
        ->count();

        $daftarAbsen = Absen::with('user:id,nama,jabatan')
        ->has('user')
        ->select([
            'user_id',
            'jam_datang',
            'jam_pulang'
        ])
        ->selectRaw("CONCAT(status, '-', kategori) as keterangan")
        ->whereDay('tanggal_absen', date('d'))
        ->get();

        $data = [
            "title" => "Dashboard",
            'totalpegawai' => $totalPegawai,
            'totalizin' => $totalIzin,
            'totalhadir' => $totalHadir,
            'totaltidakhadir' => $totalTidakHadir,
            "daftarabsen" => $daftarAbsen
        ];

        return view('Page.admin.dashboard', $data);
    }

    public function logout()
    {
        Session::flush();
        return Redirect::to('admin/');
    }
}
