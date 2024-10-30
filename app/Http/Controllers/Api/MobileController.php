<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Suratizin;
use App\Models\Suratcuti;
use App\Models\Setting;
use App\Models\Absen;
use App\Models\User;

use Session;

class MobileController extends Controller
{

    public function absenmasuk(Request $request)
    {
        $lat = $request->latitude;
        $long = $request->longitude;
        $lokasi = $request->lokasi;

        $data = [
            "lat" => $lat,
            "long" => $long,
            'lokasi' => $lokasi,
            "tanggal_absen" => date("Y-m-d"),
            "user_id" => Session::get('id')
        ];

        // get settingan dari admin
        $setting = Setting::first();
        
        $jamSekarang = date("H:i:s");
        // $jamSekarang = "08:50:00";
        $data["jam_datang"] = $jamSekarang;

        $jamMasuk = $setting->jam_masuk;

        // $maxJamMasuk = $setting->max_jam_masuk;
        // if ($jamSekarang >= $maxJamMasuk) {
        //     $data["kategori"] = "Tidak Hadir";
        // }

        if($jamSekarang < $jamMasuk) {
            $data["kategori"] = "Tepat Waktu";
            $data["status"] = "Hadir";
        } else {
            $data["kategori"] = "Terlambat";
            $data["status"] = "Hadir";
        }

        Absen::create($data);
        return [
            'status' => 'success',
            'message' => "Anda berhasil melakukan absen masuk",
            'title' => 'Berhasil!'
        ];
    }

    public function cekabsenmasuk()
    {
        $absen = Absen::where([
            'user_id' => Session::get('id'),
            'tanggal_absen' => date('Y-m-d')
        ])->first();

        if($absen) {
            
            if($absen->jam_datang !== null) {
                $jam_datang = substr($absen->jam_datang, 0, 5);
                return [
                    'status' => 'error',
                    'message' => "Anda Sudah Melakukan Absensi Masuk <br/> Pukul : <b><u>$jam_datang</u></b>",
                    'title' => ''
                ];
            }
            
            return [
                'status' => 'error',
                'message' => "Anda terlambat untuk absen",
                'title' => ''
            ];
        }
    }

    public function absenpulang()
    {
        $data = [
            'jam_pulang' => date("H:i:s")
        ];

        $absen = Absen::where([
            'user_id' => Session::get('id'),
            "tanggal_absen" => date("Y-m-d"),
        ])->update($data);

        if($absen) {
            return [
                'status' => 'success',
                'message' => "Anda berhasil melakukan absen pulang",
                'title' => 'Berhasil!'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => "gagal melakukan absen pulang",
            'title' => 'Kesalahan!'
        ];
    }

    public function cekabsenpulang()
    {
        $absen = Absen::where([
            'user_id' => Session::get('id'),
            'tanggal_absen' => date('Y-m-d')
        ])->first();

        if($absen) {
            
            if($absen->jam_pulang !== null) {
                $jam_pulang = substr($absen->jam_pulang, 0, 5);
                return [
                    'status' => 'error',
                    'message' => "Anda Sudah Melakukan Absensi Pulang <br/> Pukul : <b><u>$jam_pulang</u></b>",
                    'title' => ''
                ];
            }

            if($absen->jam_datang === null) {
                return [
                    'status' => 'error',
                    'message' => "Anda terlambat untuk absen",
                    'title' => ''
                ];
            }
            
        }
    }

    public function suratizin(Request $request) {
        $id = Session::get('id');
        $karyawan = User::where('id', $id)->first();

        $request->validate([
            'keterangan' => 'required',
            'date' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar dengan batasan tipe dan ukuran
        ]);

        $data = [
            'keterangan' => $request->keterangan,
            'tanggal_izin' => $request->date,
            'user_id' => $id,
            'status' => 'Pending'
        ];

        $file = $request->file('file');

        if ($file->isValid()) {
            $extension = $file->getClientOriginalExtension();
            $dateFolder = date('Y')."/".date('m')."/".date('d');
            $location = public_path().'/assets/image/izin/'.$dateFolder;
            $filename = $karyawan->nama."_izin_".date('H:i:s').'.'.$extension;
    
            if (!file_exists($location)) {
                File::makeDirectory(public_path().'/'.$location,0755,true);
            }

            $move = $file->move($location, $filename);

            if($move) {
                $data['file_izin'] = $dateFolder.'/'.$filename;
                Suratizin::create($data);
                return [
                    "status" => "success",
                    "title" => "Berhasil!",
                    "message" => "Surat Izin telah dibuat."
                ];
            } else {
                return [
                    "status" => "error",
                    "title" => "Kesalahan!",
                    "message" => "Surat Izin gagal dibuat."
                ];
            }

        } else {
            return [
                "status" => "error",
                "title" => "Kesalahan!",
                "message" => "File yang diunggah tidak valid."
            ];
        }

    }

    public function suratcuti(Request $request)
    {
        $id = Session::get('id');
        $karyawan = User::where('id', $id)->first();

        $request->validate([
            'keterangan' => 'required',
            'dateawal' => 'required',
            'dateakhir' => 'required',
        ]);

        $data = [
            'keterangan' => $request->keterangan,
            'tanggal_awal' => $request->dateawal,
            'tanggal_akhir' => $request->dateakhir,
            'user_id' => $id,
            'status' => 'Pending'
        ];

        Suratcuti::create($data);
        return [
            "status" => "success",
            "title" => "Berhasil!",
            "message" => "Surat Cuti telah dibuat."
        ];
    }

    public function getpengajuan(Request $request)
    {
        $kategori = $request->kategori;
        $tahun = $request->tahun;

        $suratcuti = Suratcuti::select([
            'created_at',
            'status',
            'keterangan_admin',
            'tanggal_awal',
            'tanggal_akhir'
        ])->where('user_id', Session::get('id'));

        $suratizin = Suratizin::select([
            'created_at',
            'status',
            'keterangan_admin',
            'tanggal_izin'
        ])->where('user_id', Session::get('id'));

        $results = [];

        if($tahun !== null) {
            $suratcuti = $suratcuti->whereYear('created_at', $tahun);
            $suratizin = $suratizin->whereYear('created_at', $tahun);
        }

        if ($kategori !== null) {
            if ($kategori == "cuti") {
                $results = [
                    'cuti' => $suratcuti->get()
                ];
            } elseif ($kategori == "izin") {
                $results = [
                    'izin' => $suratizin->get()   
                ];
            } else {
                $results = [
                    'cuti' => $suratcuti->get(),
                    'izin' => $suratizin->get()
                ];
            }
        } else {
            // If both $kategori and $tahun are empty, get all data
            $results = [
                'cuti' => $suratcuti->get(),
                'izin' => $suratizin->get()
            ];
        }

        return $results;
    }

    public function getprofile()
    {
        $user = User::find(Session::get('id'));

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function updateprofile(Request $request)
    {
        $id = Session::get('id');

        $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'date' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar dengan batasan tipe dan ukuran
        ]);

        $dataUser = User::where('id', Session::get('id'))->first();

        $data = [
            "nama" => $request->nama,
            "email" => $request->email,
            "tanggal_lahir" => $request->date
        ];

        if($request->password !== null) {
            $data["password"] = $request->password;
        }

        if($request->file !== null) {
            
            $file = $request->file('file');

            if ($file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                $location = public_path().'/assets/image/karyawan';
                $filename = $id.'.'.$extension;

                if (!file_exists($location)) {
                    File::makeDirectory(public_path().'/'.$location,0755,true);
                }
    
                // jika sudah ada photo maka update dan hapus
                if($dataUser->photo !== "") {
                    unlink($location.'/'.$dataUser->photo);
                    
                    $dataUser->update([
                        "photo" => ""
                    ]);
    
                }
    
                $file->move($location, $filename);
                $data['photo'] = $filename;
            } else {
                return [
                    "status" => "error",
                    "title" => "Kesalahan!",
                    "message" => "File yang diunggah tidak valid."
                ];
            }
        }

        $updateUser = $dataUser->update($data);

        if(!$updateUser) {
            return [
                "status" => "error",
                "title" => "Kesalahan!",
                "message" => "Gagal Update Profile"
            ];
        }

        return [
            "status" => "success",
            "title" => "Berhasil!",
            "message" => "Berhasil Update Profile"
        ];
    }
}
