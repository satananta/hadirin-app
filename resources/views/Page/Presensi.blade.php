@extends('layouts.app')

@section('head')
    <title>Presensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endsection

@section('content')
    <x-header class="flex justify-center items-center h-44">
        <div class="bg-[#FE976B] text-center p-3 my-3 rounded-full">
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
        </div>
    </x-header>
    <x-content>
        {{-- info rekap presensi / bulan --}}
        <div class="flex flex-col justify-center items-center">
            <x-info-header>
                <div class="grid grid-cols-2 text-center text-lg divide-x divide-gray-400">
                    <div class="flex flex-col items-center">
                        <span class="font-bold">Jam Datang</span>
                        <span class="text-[#44B156] text-3xl">{{ substr($records[date('d')-1]->jam_datang, 0, 5)  }}</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="font-bold">Jam Pulang</span>
                        <span class="text-[#44B156] text-3xl">{{ substr($records[date('d')-1]->jam_pulang, 0, 5) }}</span>
                    </div>
                </div>
            </x-info-header>

            <div class="mt-20 w-11/12 h-full mb-16">
                <p class="font-bold text-sm md:text-md">Rekap Presensi</p>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Datang / Pulang</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                        <tr class="bg-white border-b text-xs landscape:text-base">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($record->tanggal_absen)->isoFormat('dddd, D MMMM Y') }}</td>
                            @if($record->status == "Tidak Hadir" && $record->kategori == "Terlambat")
                                <td class="px-6 py-4 text-red-500">Tidak Hadir</td>
                            @elseif($record->status == "Tidak Hadir" && ($record->kategori == "Izin" || $record->kategori == "Cuti") )
                                <td class="px-6 py-4 text-yellow-500">{{$record->kategori}}</td>
                            @else
                                <td class="px-6 py-4">
                                    {{ substr($record->jam_datang, 0, 5)}} / {{ substr($record->jam_pulang, 0, 5)}}
                                </td>
                            @endif
                            
                            <td 
                            class="px-6 py-4 detail-presensi" 
                            data-jamdatang="{{ substr($record->jam_datang, 0, 5) }}"
                            data-jampulang="{{ substr($record->jam_pulang, 0, 5)}}"
                            data-tanggalabsen="{{ \Carbon\Carbon::parse($record->tanggal_absen)->isoFormat('dddd, D MMMM Y') }}"
                            data-kategori="{{$record->kategori}}"
                            data-status="{{$record->status}}"
                            data-lokasi="{{$record->lokasi}}"
                            >
                                <i class='bx bx-dots-vertical-rounded'></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-content>

    <x-footer />

    <div id="overlay" class="fixed bottom-0 inset-0 bg-black bg-opacity-70 z-50 hidden">
        <div id="closeOverlay" class="flex items-end justify-center h-full">
            <div id="bodyOverlay" class="bg-white p-4 rounded-md w-full">

                <div class="flex flex-row border-b-2 border-black">
                    <p class="flex-auto font-bold" id="tanggalabsen"></p>
                    <span class="font-bold flex-none mb-2" id="status"></span>
                </div>

                <div class="grid grid-cols-2 border-b-2 border-black">
                    <div class="mx-auto my-2">
                        <div class="flex flex-col mx-auto">
                            <p class="text-gray-400">Jam Datang</p>
                            <span class="flex-auto font-bold text-center" id="jamdatang"></span>
                        </div>
                    </div>

                    <div class="mx-auto my-2">
                        <div class="flex flex-col mx-auto">
                            <p class="text-gray-400">Jam Pulang</p>
                            <span class="flex-auto font-bold text-center" id="jampulang"></span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col border-b-2 border-black">
                    <div class="my-2">
                        <p class="text-gray-400">Lokasi</p>
                        <span class="font-bold" id="lokasi"></span>
                    </div>
                </div>

                <div class="flex flex-col border-b-2 border-black">
                    <div class="my-2">
                        <p class="text-gray-400">Keterangan</p>
                        <span class="font-bold" id="kategori"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {

            function showOverlay() {
                $("#overlay").removeClass("hidden");
                $("#bodyOverlay").addClass("animate__animated animate__fadeInUp");
                $("body").addClass("!overflow-hidden");
            }

            function hideOverlay() {
                $("#bodyOverlay").addClass("animate__animated animate__fadeOutDown");

                // Add a delay to match the animation duration
                setTimeout(function() {
                    $("#overlay").addClass("hidden");
                    $("#bodyOverlay").removeClass("animate__fadeOutDown");
                    $("body").removeClass("!overflow-hidden");
                }, 500);
            }

            $("#overlay").click(function(e) {
                if (e.target.id === "closeOverlay") {
                    hideOverlay();
                }
            });

            $("#bodyOverlay").click(function(e) {
                e.stopPropagation(); // Prevent the click event from reaching the #overlay element
            });

            $(".detail-presensi").click(function() {
                var jamdatang = $(this).data('jamdatang')
                var jampulang = $(this).data('jampulang')
                var tanggalabsen = $(this).data('tanggalabsen')
                var kategori = $(this).data('kategori');
                var lokasi = $(this).data('lokasi')
                var status = $(this).data('status')

                // remove all class
                $("#keterangan").removeClass("text-red-500");
                $("#keterangan").removeClass("text-yellow-500");
                $("#keterangan").removeClass("text-green-500");

                var maps = ['jamdatang', 'jampulang', 'tanggalabsen', 'kategori', 'lokasi', 'status'];
                maps.map(function(key) {
                    $(`#${key}`).html(eval(key));
                    $(`#${key}`).removeClass('text-red-500');
                    $(`#${key}`).removeClass('text-yellow-500');
                    $(`#${key}`).removeClass('text-green-500');
                });

                if(status == 'Hadir') {
                    $("#status").addClass('text-green-500');
                } else {
                    $("#status").addClass('text-red-500');
                }

                if(kategori == "Terlambat") {
                    $("#keterangan").addClass("text-red-500");
                    $("#kategori").addClass("text-red-500");
                } else if (kategori == "Izin" || kategori == "Cuti") {
                    $("#keterangan").addClass("text-yellow-500");
                    $("#kategori").addClass("text-yellow-500");
                } else {
                    $("#keterangan").addClass("text-green-500");
                    $("#kategori").addClass("text-green-500");
                }

                showOverlay();
            });
        })
    </script>
@endsection
