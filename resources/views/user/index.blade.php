@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Master Karyawan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Data Karyawan</li>
        </ol>
        <a href="{{route('admin.user.create')}}" class="btn btn-primary">Tambah Karyawan</a>
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Daftar Karyawan
            </div>
            <div class="card-body">
                @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{session()->get('success')}}
                </div>
                @endif
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Lahir</th>
                            <th>Jabatan</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->nip}}</td>
                            <td>{{$user->nama}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{
                                \Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat('dddd, D MMMM Y')
                            }}</td>
                            <td>{{$user->jabatan}}</td>
                            <td>{{$user->level}}</td>
                            <td>
                                <a href="{{route('admin.user.edit', $user->id)}}" class="btn btn-warning mb-3">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button class="btn btn-danger delete mb-3" data-id="{{$user->id}}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>               
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".delete").on( "click", function() {
        var id = $(this).data('id');
        var url = "{{route('admin.user.destroy', ':id')}}";
        url = url.replace(':id', id);

        $.ajax({
            url,
            method: "POST",
            success: function() {
                Swal.fire('Berhasil!', 'Karywan Berhasil dihapus', 'success').then(() => window.location.reload());
            }
        })
    })
})
</script>
@endsection
