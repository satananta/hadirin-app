<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratizinUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Suratizin;
use App\Models\Absen;

class SuratizinController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suratizins = Suratizin::all();
        $title = "Pengajuan Izin";

        return view('suratIzin.index', compact('suratizins', 'title'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Suratizin $suratizin
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Suratizin $suratizin)
    {
        return view('suratIzin.show', compact('suratizin'));
    }

    /**
     * @param \App\Http\Requests\SuratizinUpdateRequest $request
     * @param \App\Models\Suratizin $suratizin
     * @return \Illuminate\Http\Response
     */
    public function update(SuratizinUpdateRequest $request, Suratizin $suratizin)
    {
        $suratizin->update($request->validated());

        if($request->status == "Terima") {
            $cekAbsen = Absen::where([
                'tanggal_absen' => $suratizin->tanggal_izin,
                'user_id' => $suratizin->user_id,
            ])->exists();

            if(!$cekAbsen) {
                $data = [
                    'tanggal_absen' => $suratizin->tanggal_izin,
                    'kategori' => 'Izin',
                    'status' => 'Tidak Hadir',
                    'user_id' => $suratizin->user_id
                ];
    
                Absen::create($data);
            }

        }

        return response()->json([
            'success' => true,
        ]); 
    }
}
