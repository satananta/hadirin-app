@extends('layouts.app')

@section('head')
    <title>Pengajuan Izin</title>
@endsection

@section('content')
    <x-header class="flex h-20 -mt-6 -ml-8">
        <a href="{{ route('mobile.views.home') }}" class="flex-none text-2xl">
            <i class='bx bx-chevron-left'></i>
        </a>
        <div class="grow text-center text-xl">
            <h2>Pengajuan Izin</h2>
        </div>
    </x-header>

    <x-content class="mb-20">
        <div class="flex flex-col justify-center items-center bg-[#44B156] h-80">
            <div class="h-full w-11/12 md:w-12/12 mt-5">
                <div id="errorMsg" class="bg-red-500 p-3 mb-3 text-white hidden"></div>
                <form id="form" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="text-white" for="keterangan">Keterangan</label>
                        <textarea
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            name="keterangan" id="keterangan" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="text-white" for="surat">Surat Izin</label>
                        <input type="file" id="file" name="file" />
                    </div>
                    <div class="mb-44">
                        <label class="text-white" for="date">Tanggal Izin</label>
                        <input type="date" name="date" id="date"
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            placeholder="Masukan Tanggal" />
                    </div>
                </form>
            </div>
        </div>
    </x-content>

    <x-footer-green>
        <p class="text-white text-center text-lg">Tolong Pastikan Anda Telah Cek kembali...</p>
        <x-button-surat id="submit">Submit</x-button-surat>
    </x-footer-green>
@endsection

@section('javascript')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#submit").click(function() {
                var form = $("#form")[0]; // Ambil elemen HTML formulir
                var formData = new FormData(form); // Buat objek FormData

                $.ajax({
                    url: "{{ route('mobile.api.suratizin') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // Penting untuk FormData
                    processData: false, // Penting untuk FormData
                    success: function(data) {
                        $("#errorMsg").hide();
                        if (data.status == "success") {
                            return swal(data.title, data.message, data.status).then(() =>
                                window.location = "{{ route('mobile.views.cekstatus') }}");
                        }
                        swal(data.title, data.message, data.status);

                    },
                    error: function(response) {
                        var errorContainer = $("#errorMsg");
                        var errors = response.responseJSON.errors;

                        errorContainer.empty(); // Bersihkan pesan sebelumnya
                        errorContainer.show();

                        errorContainer.append("<ul>");
                        $.each(errors, function(field, messages) {
                            // Tampilkan pesan kesalahan untuk setiap bidang
                            $.each(messages, function(index, message) {
                                errorContainer.append("<li>" +
                                    message + "</li>");
                            });
                        });
                        errorContainer.append("</ul>");
                    }
                })
            })
        })
    </script>
@endsection
