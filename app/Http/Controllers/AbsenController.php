<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\Setting;

class AbsenController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $absens = Absen::with('user:id,nama,jabatan,photo')
        ->has('user')
        ->select([
            'user_id',
            'jam_datang',
            'jam_pulang',
            'lat',
            'long',
            'lokasi'
        ])
        ->whereDay('tanggal_absen', date('d'))
        ->whereNotNull('lat')
        ->whereNotNull('long')
        ->get();

        $settings = Setting::first();
        $title = 'Histori Absen';

        return view('absen.index', compact('absens', 'settings', 'title'));
    }
}
