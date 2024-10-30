@extends('layouts.app')

@section('head')
    <title>Profile</title>
@endsection

@section('content')
    <x-header class="flex justify-center items-center h-44">
        <p class="text-2xl">Edit Profile</p>
    </x-header>

    <x-content>
        <div class="flex flex-col justify-center items-center">
            <div
                class="absolute left-1/2 top-44 transform -translate-x-1/2 -translate-y-1/2 border border-red-500 rounded-full border-2">
                <img class="rounded-full w-24 h-24" />
            </div>
            <div class="h-full w-10/12 md:w-12/12 mt-10 mb-20">
                <div id="errorMsg" class="bg-red-500 p-3 mb-3 text-white hidden"></div>
                <form id="form" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="nama">Name</label>
                        <input type="text" name="nama" id="nama"
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            placeholder="Masukan Nama Anda" />
                    </div>
                    <div class="mb-4">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email"
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            placeholder="Masukan email atau NIP" />
                    </div>
                    <div class="mb-4">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password"
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            placeholder="Masukan Password" />
                    </div>
                    <div class="mb-4">
                        <label for="date">Date of Birth</label>
                        <input type="date" name="date" id="date"
                            class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                            placeholder="Masukan Tanggal" />
                    </div>
                    <div class="mb-4">
                        <label for="surat">Photo</label>
                        <input type="file" id="file" name="file" />
                    </div>
                </form>
                <x-button id="save" class="bg-[#44B156] text-white w-full">Save Changes</x-button>
            </div>
        </div>
    </x-content>

    <x-footer />
@endsection

@section('javascript')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            function showProfile() {

                $.ajax({
                    url: "{{ route('mobile.api.getprofile') }}",
                    method: 'GET',
                    success: function(data) {
                        var {
                            nama,
                            email,
                            photo,
                            tanggal_lahir
                        } = data;
                        var formattedDate = moment(tanggal_lahir).format('YYYY-MM-DD');
                        var imgsrc = (photo === "" ? "{{ asset('assets/image/pp.png') }}" : `{{asset('assets/image/karyawan/${photo}')}}`)

                        $("#nama").val(nama);
                        $("#email").val(email);
                        $("#date").val(formattedDate);
                        $("img").attr('src', imgsrc);
                        $("#password").val("");
                        $("#file").val("");

                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            }

            showProfile();

            $("#save").click(function() {
                swal({
                    title: "Apakah Kamu Yakin?",
                    text: "Apakah Anda ingin menyimpan perubahan?",
                    icon: "warning",
                    buttons: true,
                }).then((result) => {
                    if (result) {
                        var form = $("#form")[0]; // Ambil elemen HTML formulir
                        var formData = new FormData(form); // Buat objek FormData

                        $.ajax({
                            url: "{{ route('mobile.api.updateprofile') }}",
                            type: "POST",
                            data: formData,
                            contentType: false, // Penting untuk FormData
                            processData: false, // Penting untuk FormData
                            success: function(data) {
                                showProfile();
                                $("#errorMsg").hide();
                                return swal(data.title, data.message, data.status)
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
                        });
                    } else {
                        swal("Perubahan tidak disimpan", "", "info");
                        showProfile();
                    }
                });
            })
        })
    </script>
@endsection
