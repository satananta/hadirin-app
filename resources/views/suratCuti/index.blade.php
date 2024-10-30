@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Surat Cuti</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Pengajuan Cuti</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Daftar Pengajuan
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Keterangan Karyawan</th>
                            <th>Tanggal Awal</th>
                            <th>Tanggal Akhir</th>
                            <th>Persetujuan - Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suratcutis as $suratcuti)
                            <tr>
                                <td>{{ $suratcuti->user->nama }}</td>
                                <td>{{ substr($suratcuti->keterangan, 0, 25) }}</td>
                                <td>{{ \Carbon\Carbon::parse($suratcuti->tanggal_awal)->isoFormat('dddd, D MMMM Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($suratcuti->tanggal_akhir)->isoFormat('dddd, D MMMM Y') }}</td>
                                <td>{{ $suratcuti->status }} - {{ $suratcuti->keterangan_admin }}</td>
                                <td>
                                    @if ($suratcuti->status == "Pending")
                                    <button 
                                        class="btn btn-primary proses" 
                                        data-id="{{ $suratcuti->id }}"
                                        data-nama="{{ $suratcuti->user->nama }}"
                                        data-ket="{{ $suratcuti->keterangan}}"
                                        data-tgl_awal="{{ \Carbon\Carbon::parse($suratcuti->tanggal_awal)->isoFormat('dddd, D MMMM Y') }}"
                                        data-tgl_akhir="{{ \Carbon\Carbon::parse($suratcuti->tanggal_akhir)->isoFormat('dddd, D MMMM Y') }}"
                                        data-user_id="{{$suratcuti->user->id}}"
                                    >
                                        Proses
                                    </button>  
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="myModalLabel">Konfirmasi Cuti</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light w-25">Nama</th>
                            <td id="nama"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Keterangan Karyawan</th>
                            <td id="ket"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Tanggal Awal</th>
                            <td id="tgl_awal"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Tanggal Akhir</th>
                            <td id="tgl_akhir"></td>
                        </tr>
                    </table>
                    <form id="form">
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="user_id" id="user_id" />
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">=== Pilih Status ===</option>
                                <option value="Terima">Setujui</option>
                                <option value="Tolak">Tidak Setujui</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan_admin" id="keterangan" cols="30" rows="10" class="form-control" placeholder="Masukan Keterangan Admin"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirm">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            const myModal = new bootstrap.Modal(document.getElementById('myModal'))

            $(".proses").click(function() {

                myModal.show();

                const id = $(this).data('id');
                const user_id = $(this).data('user_id');
                const nama = $(this).data('nama');
                const ket = $(this).data('ket');
                const tgl_awal = $(this).data('tgl_awal');
                const tgl_akhir = $(this).data('tgl_akhir');

                $("#id").val(id);
                $("#user_id").val(user_id);
                $("#nama").html(nama);
                $("#ket").html(ket);
                $("#tgl_awal").html(tgl_awal);
                $("#tgl_akhir").html(tgl_akhir);

            });

            $("#confirm").click(function(){
                const status = $("#status").val();
                const ket = $("#keterangan").val();

                if(status.trim() === "" && ket.trim() === "")
                {
                    return Swal.fire('Kesalahan!', 'Status dan keterangan tidak boleh kosong', 'error');
                }

                const form = $("#form").serialize();
                let url = "{{route('admin.suratcuti.update', ':id')}}"
                url = url.replace(':id',  $("#id").val());

                $.ajax({
                    url,
                    method: 'POST',
                    data: form,
                    success: function(data) {
                        if(data.success) {
                            return Swal.fire('Berhasil!', 'Pemohonan cuti telah diubah', 'success').then(() => {
                                myModal.hide();
                                window.location.reload();
                            });
                        }
                    }
                })
            });
        })
    </script>
@endsection
