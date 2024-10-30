@extends('layouts.app')

@section('head')
    <title>Cek Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endsection

@section('content')
    <x-header class="flex h-20 -mt-6 -ml-8">
        <a href="{{ route('mobile.views.home') }}" class="flex-none text-2xl">
            <i class='bx bx-chevron-left'></i>
        </a>
        <div class="grow text-center text-xl">
            <h2>Cek Status</h2>
        </div>
    </x-header>

    <x-content class="!mt-0">
        {{-- info status --}}
        <div class="flex flex-col justify-center items-center">
            <div class="mt-8 w-11/12 h-full mb-16">
                <div class="flex flex-row">
                    <div class="flex-auto">
                        <p class="font-bold text-sm md:text-md">Rekap Izin / Cuti</p>
                    </div>
                    <p class="text-sm md:text-md" id="filter">
                        <i class='bx bx-filter-alt'></i>
                    </p>
                </div>
                <div class="bg-gray-300 p-3 hidden mb-5" id="infofilter">
                    <div class="mb-4">
                        <label class="font-bold" for="nama">Tahun</label>
                        <select name="tahun" id="tahun"
                            class="block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0">
                            <option value="">Pilih Tahun</option>
                            @for ($i = 2023; $i >= 2013; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="font-bold" for="nama">Kategori</label>
                        <select name="kategori" id="kategori"
                            class="block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0">
                            <option value="">Pilih Kategori</option>
                            <option value="izin">Izin</option>
                            <option value="cuti">Cuti</option>
                        </select>
                    </div>
                </div>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Keterangan</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody id="listizin"></tbody>
                </table>
            </div>
        </div>
    </x-content>
    <div id="overlay" class="fixed bottom-0 inset-0 bg-black bg-opacity-70 z-50 hidden">
        <div id="closeOverlay" class="flex items-end justify-center h-full">
            <div id="bodyOverlay" class="bg-white p-4 rounded-md w-full">
                <div class="flex flex-row border-b-2 border-black">
                    <p class="flex-auto font-bold">{{ \Carbon\Carbon::now()->addDays()->isoFormat('dddd, D MMMM Y') }}</p>
                    <span class="flex-none font-bold mb-2" id="title">Izin</span>
                </div>

                <div class="flex flex-col border-b-2 border-black">
                    <div class="my-2">
                        <p class="text-gray-400">Status</p>
                        <span class="font-bold" id="status"></span>
                    </div>
                </div>

                <div class="flex flex-col border-b-2 border-black">
                    <div class="my-2">
                        <p class="text-gray-400">Keterangan</p>
                        <span class="font-bold" id="keterangan"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
    function formatDate(inputDate) {
        // Parse the input date string using Moment.js
        const momentDate = moment(inputDate);

        // Set the locale to Bahasa Indonesia
        momentDate.locale('id');

        // Format the date using the desired format
        const formattedDate = momentDate.format('dddd, D MMMM YYYY');

        return formattedDate;
    }

    async function getPengajuan(tahun = "", kategori = "") {
        $("#listizin").remove();
        $("table").append('<tbody id="listizin"></tbody>')

        var data = await $.post({
            url: "{{ route('mobile.api.getpengajuan') }}",
            data: {
                tahun,
                kategori
            }
        });

        // Assuming that "cuti" and "izin" are arrays inside the returned data
        if (data.cuti && data.cuti.length > 0) {
            data.cuti.forEach((item) => {
                // Append HTML for "cuti" to #listizin
                $('#listizin').append(`
                <tr class="bg-white border-b text-xs landscape:text-base">
                    <td class="px-6 py-4">${formatDate(item.created_at)}</td>
                    <td class="px-6 py-4">
                        Cuti (${formatDate(item.tanggal_awal)} - ${formatDate(item.tanggal_akhir)}) -
                        <span class="font-bold ${
                            item.status === 'Pending'
                                ? 'text-yellow-500'
                                : item.status === 'Terima'
                                ? 'text-green-500'
                                : 'text-red-500'
                        }">${item.status === 'Pending' ? 'Diproses' : 'Di'+item.status.toLowerCase()}</span>
                    </td>
                    <td class="px-6 py-4 detail-presensi" data-tanggal="${formatDate(item.created_at)}" data-status="${item.status === 'Pending' ? 'Diproses' : 'Di'+item.status.toLowerCase()}" data-keterangan="${item.keterangan_admin}" data-title="Cuti">
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </td>
                </tr>
            `);
            });
        }

        if (data.izin && data.izin.length > 0) {
            data.izin.forEach((item) => {
                // Append HTML for "izin" to #listizin
                $('#listizin').append(`
                <tr class="bg-white border-b text-xs landscape:text-base">
                    <td class="px-6 py-4">${formatDate(item.created_at)}</td>
                    <td class="px-6 py-4">
                        Izin (${formatDate(item.tanggal_izin)}) -
                        <span class="font-bold ${
                            item.status === 'Pending'
                                ? 'text-yellow-500'
                                : item.status === 'Terima'
                                ? 'text-green-500'
                                : 'text-red-500'
                        }">${item.status === 'Pending' ? 'Diproses' : 'Di'+item.status.toLowerCase()}</span>
                    </td>
                    <td class="px-6 py-4 detail-presensi" data-tanggal="${formatDate(item.created_at)}" data-status="${item.status === 'Pending' ? 'Diproses' : 'Di'+item.status.toLowerCase()}" data-keterangan="${item.keterangan_admin}" data-title="Izin">
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </td>
                </tr>
            `);
            });
        }

    }

    getPengajuan();

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

    $('#listizin').on('click', '.detail-presensi', function() {
        var status = $(this).data('status');
        var tanggal = $(this).data('tanggal');
        var keterangan = $(this).data('keterangan');
        var title = $(this).data('title');

        var maps = ['status', 'tanggal', 'keterangan', 'title'];
        maps.map(function(key) {
            $(`#${key}`).html(eval(key))
            $(`#${key}`).removeClass('text-red-500')
            $(`#${key}`).removeClass('text-yellow-500')
            $(`#${key}`).removeClass('text-green-500')
        });

        if (status == 'Diproses') {
            $("#status").addClass('text-yellow-500')
        } else if (status == 'Diterima') {
            $("#status").addClass('text-green-500')
        } else {
            $("#status").addClass('text-red-500')
        }

        showOverlay();
    });

    $("#filter").on("click", function() {
        $("#infofilter").toggle();
    });

    $("#kategori, #tahun").change(function() {
        // Mendapatkan nilai dari elemen 'kategori'
        var kategori = $("#kategori").val();
        
        // Mendapatkan nilai dari elemen 'tahun'
        var tahun = $("#tahun").val();

        // Memanggil fungsi getPengajuan dengan kedua nilai yang didapatkan
        getPengajuan(tahun, kategori);
    });
})
    </script>
@endsection
