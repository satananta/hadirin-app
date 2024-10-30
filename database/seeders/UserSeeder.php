<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insertMulti = [
            [
                'nama' => 'Rizsyad AR',
                'nip' => '000000',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
                'tanggal_lahir' => '2002-06-01',
                'photo' => '',
                'level' => 'admin',
                'jabatan' => 'CEO'
            ],
            [
                'nama' => 'Karyawan 1',
                'nip' => '000001',
                'email' => 'karyawan1@gmail.com',
                'password' => bcrypt('123456'),
                'tanggal_lahir' => '2002-07-01',
                'photo' => '',
                'level' => 'karyawan',
                'jabatan' => 'Manager'
            ],
            [
                'nama' => 'Karyawan 2',
                'nip' => '000002',
                'email' => 'karyawan2@gmail.com',
                'password' => bcrypt('123456'),
                'tanggal_lahir' => '2002-07-01',
                'photo' => '',
                'level' => 'karyawan',
                'jabatan' => 'IT Support'
            ],
            [
                'nama' => 'Karyawan 3',
                'nip' => '000003',
                'email' => 'karyawan3@gmail.com',
                'password' => bcrypt('123456'),
                'tanggal_lahir' => '2002-07-01',
                'photo' => '',
                'level' => 'karyawan',
                'jabatan' => 'IT Support'
            ]
        ];
        
        User::create($insertMulti[0]);
        User::create($insertMulti[1]);
        User::create($insertMulti[2]);
        User::create($insertMulti[3]);
    }
}
