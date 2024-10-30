@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Master Karyawan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Tambah Karyawan</li>
        </ol>
        <form action="{{ route('admin.user.store') }}" method="post">
            @csrf
            <div class="form-group mb-3">
                <label for="nip">NIP</label>
                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" type="number"
                    placeholder="Masukan NIM" value="{{old('nip')}}" />
                @error('nip')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                    placeholder="Masukan Nama" value="{{old('nama')}}" />
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" placeholder="Masukan Email" value="{{old('email')}}" />
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Masukan Password" value="{{old('password')}}" />
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                    class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{old('tanggal_lahir')}}" />
                @error('tanggal_lahir')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="jabatan">Jabatan</label>
                <select name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror">
                    <option value="">=== Pilih Jabatan ===</option>
                    <option value="CEO" {{ old('jabatan') === "CEO" ? "selected" : ""  }}>CEO</option>
                    <option value="Manager" {{ old('jabatan') === "Manager" ? "selected" : ""  }}>Manager</option>
                    <option value="IT Support" {{ old('jabatan') === "IT Support" ? "selected" : ""  }}>IT Support</option>
                </select>
                @error('jabatan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="level">Level</label>
                <select name="level" id="level" class="form-control @error('level') is-invalid @enderror">
                    <option value="">=== Pilih Jabatan ===</option>
                    <option value="admin"  {{ old('level') === "admin" ? "selected" : ""  }}>admin</option>
                    <option value="karyawan" {{ old('level') === "karywan" ? "selected" : ""  }}>Karyawan</option>
                </select>
                @error('level')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
