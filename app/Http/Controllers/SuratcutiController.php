<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratcutiUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Suratcuti;
use App\Models\Absen;

class SuratcutiController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suratcutis = Suratcuti::with('user:id,nama')
        ->has('user')
        ->whereYear('created_at', date('Y'))
        ->orderByRaw("FIELD(status, 'Pending', 'Terima', 'Tolak')")
        ->get();

        $title = "Pengajuan Cuti";

        return view('suratCuti.index', compact('suratcutis', 'title'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Suratcuti $suratcuti
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Suratcuti $suratcuti)
    {
        return view('suratCuti.show', compact('suratcuti'));
    }

    /**
     * @param \App\Http\Requests\SuratcutiUpdateRequest $request
     * @param \App\Models\Suratcuti $suratcuti
     * @return \Illuminate\Http\Response
     */
    public function update(SuratcutiUpdateRequest $request, Suratcuti $suratcuti)
    {
        $suratcuti->update($request->validated());

        if($request->status == "Terima") {
            // Array untuk menyimpan data cuti
            $data = [];
    
            // Tanggal untuk looping
            $startDate = $suratcuti->tanggal_awal;
            $endDate = $suratcuti->tanggal_akhir;
    
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'))) {
                $cekAbsen = Absen::where([
                    'tanggal_absen' => $currentDate,
                    'user_id' => $suratcuti->user_id,
                ])->exists();

                if(!$cekAbsen) {
                    $data[] = [
                        'tanggal_absen' => $currentDate,
                        'kategori' => 'Cuti',
                        'status' => 'Tidak Hadir',
                        'user_id' => $suratcuti->user_id
                    ];
                }
            }
    
            Absen::insert($data);
        }

        return response()->json([
            'success' => true,
        ]); 
    }
}
