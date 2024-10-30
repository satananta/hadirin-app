@extends('layouts.app')

@section('head')
    <title>Pengajuan Cuti</title>
@endsection

@section('content')
    <x-header class="flex h-20 -mt-6 -ml-8">
        <a href="{{ route('mobile.views.home') }}" class="flex-none text-2xl">
            <i class='bx bx-chevron-left'></i>
        </a>
        <div class="grow text-center text-xl">
            <h2>Pengajuan Cuti</h2>
        </div>
    </x-header>

    <x-content class="mb-20">
        <div class="flex flex-col justify-center items-center bg-[#44B156] h-80">
            <div class="h-full w-11/12 md:w-12/12 mt-5">
                <div id="errorMsg" class="bg-red-500 p-3 mb-3 text-white hidden"></div>
                <form id="form">
                    <div class="my-4">
                        <label class="text-white" for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan"
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            placeholder="Masukan Keterangan"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="text-white" for="dateawal">Tangga Awal</label>
                        <input type="date" name="dateawal" id="dateawal"
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            placeholder="Masukan Tanggal" />
                    </div>
                    <div class="mb-44">
                        <label class="text-white" for="dateakhir">Tanggal Akhir</label>
                        <input type="date" name="dateakhir" id="dateakhir"
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
                var form = $("#form").serialize();
                $.ajax({
                    url: "{{ route('mobile.api.suratcuti') }}",
                    type: "POST",
                    data: form,
                    success: function(data) {
                        $("#errorMsg").hide();
                        $("#content > main > div").removeClass("h-[26rem]");
                        $("#content > main > div").addClass("h-80");

                        return swal(data.title, data.message, data.status).then(() =>
                            window.location = "{{ route('mobile.views.cekstatus') }}");
                    },
                    error: function(response) {
                        var errorContainer = $("#errorMsg");
                        var errors = response.responseJSON.errors;

                        errorContainer.empty(); // Bersihkan pesan sebelumnya
                        errorContainer.show();
                        $("#content > main > div").removeClass("h-80");
                        $("#content > main > div").addClass("h-[26rem]");

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
            });
        })
    </script>
@endsection
