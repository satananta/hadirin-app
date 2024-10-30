<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\Setting;
use Carbon\Carbon;


use Session;

class ViewController extends Controller
{
    public function home()
    {
        $setting = Setting::first();
        $maxJamMasuk = $setting->max_jam_masuk;
        $jamSekarang = date('H:i:s');

        if($jamSekarang >= $maxJamMasuk) {
            $cekAbsen = Absen::where([
                'tanggal_absen' => date('Y-m-d'),
                'user_id' => Session::get('id'),
            ])->exists();
            
            if(!$cekAbsen) {
                Absen::create([
                    'user_id' => Session::get('id'),
                    'Kategori' => 'Terlambat',
                    'status' => 'Tidak Hadir',
                    'tanggal_absen' => date('Y-m-d')
                ]);
            }
        }

        $hadir = Absen::where([
            'status' => 'Hadir',
            'user_id' => Session::get('id')
        ])
        ->whereYear('tanggal_absen', date('Y'))
        ->count();

        $tidak = Absen::where([
            'status' => 'Tidak Hadir',
            'user_id' => Session::get('id')
        ])
        ->whereNotIn('kategori', ['Izin', 'Cuti'])
        ->whereYear('tanggal_absen', date('Y'))
        ->count();

        $izinOrCuti = Absen::where('user_id', Session::get('id'))
        ->whereIn('kategori', ['Izin', 'Cuti'])
        ->whereYear('tanggal_absen', date('Y'))
        ->count();

        return view('Page.Home', [
            "hadir" => $hadir,
            "izin" => $izinOrCuti,
            "tidak" => $tidak
        ]);
    }

    public function presensi()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $data = Absen::where('user_id', Session::get('id'))
        ->whereBetween(DB::raw('DATE(tanggal_absen)'), [$startDate->toDateString(), $endDate->toDateString()])
        ->get();
        
        $records = [];

        // Loop through each day of the month
        for ($currentDate = $startDate->copy(); $currentDate->lessThanOrEqualTo($endDate); $currentDate->addDay()) {
            $record = $data->first(function ($record) use ($currentDate) {
                return Carbon::parse($record->tanggal_absen)->toDateString() == $currentDate->toDateString();
            });

            $records[] = (object) [
                'tanggal_absen' => $currentDate->toDateString(),
                'jam_datang' => $record ? ($record->jam_datang ?: '-') : '-',
                'jam_pulang' => $record ? ($record->jam_pulang ?: '-') : '-',
                'kategori' => $record ? $record->kategori : '',
                'status' => $record ? $record->status : '',
                'lokasi' => $record ? $record->lokasi : '-',
            ];
        }

        return view('Page.Presensi', compact('records'));
    }

    public function profile()
    {
        return view('Page.Profile');
    }

    public function absenmasuk()
    {
        $setting = Setting::first();

        return view('Page.absenmasuk', compact('setting'));
    }

    public function absenpulang()
    {
        $setting = Setting::first();

        return view('Page.absenpulang', compact('setting'));
    }

    public function suratizin()
    {
        return view('Page.suratizin');
    }

    public function pengajuancuti()
    {
        return view('Page.pengajuancuti');
    }

    public function cekstatus()
    {
        return view('Page.cekstatus');
    }
}
