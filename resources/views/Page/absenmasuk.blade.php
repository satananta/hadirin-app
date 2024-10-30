@extends('layouts.app')

@section('head')
    <title>Absen Masuk</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('content')
    <x-header class="flex h-20 -mt-6 z-10">
        <a href="{{ route('mobile.views.home') }}" class="flex-none text-2xl">
            <i class='bx bx-chevron-left'></i>
        </a>
        <div class="grow text-center text-xl">
            <h2>Absen Masuk</h2>
        </div>
    </x-header>

    <x-content class="!-mt-20 z-0 !mx-0">
        <div class="h-full w-screen absolute" id="map"></div>
        <input type="hidden" id="lat" />
        <input type="hidden" id="long" />
        <input type="hidden" id="lokasi" />
    </x-content>

    <x-footer-green>
        <p class="text-white text-center text-lg">Tolong Pastikan Anda dalam radius...</p>
        <x-button-surat id="konfbutton">Konfirmasi Lokasi</x-button-surat>
    </x-footer-green>
@endsection

@section('javascript')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(document).ready(async () => {

            async function cekabsen()
            {
                var data = await $.get("{{route('mobile.api.cekabsenmasuk')}}");
                if(data) {
                    return swal(data.title, data.message, data.status).then(() => window.location = "{{route('mobile.views.home')}}");
                }
            }

            await cekabsen();
            
            var permissionStatus = await navigator?.permissions?.query({
                name: 'geolocation'
            })
            var hasPermission = permissionStatus?.state // Dynamic value

            if (hasPermission == "denied") {
                return swal('Geolocation denied', 'Location must be enabled.', 'error').then(() => window.location = "{{route('mobile.views.home')}}");
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(setPosition);
            } else {
                return swal('Geolocation denied', 'Geolocation is not supported by this browser.', 'error').then(() => window.location = "{{route('mobile.views.home')}}");
            }

            var codinate = [{{$setting->lat}}, {{$setting->long}}];
            var map = L.map('map').setView(codinate, 17);
            L.circle(codinate, {{$setting->radius}}).addTo(map);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            async function setPosition(position) {
                //  lokasi yang akurat
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                $("#lat").val(latitude);
                $("#long").val(longitude);
                
                // get nama lokasi dari coodinat yang diberikan
                var nominatim = await $.get(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`);
                var lokasi = await nominatim?.name || nominatim?.display_name;
                $("#lokasi").val(lokasi);

                var BLLocation = new L.LatLng(codinate[0], codinate[1]);
                var myLocation = new L.LatLng(latitude, longitude);

                // Create a marker using the obtained latitude and longitude
                L.marker([latitude, longitude]).addTo(map).bindPopup('Your Here');
                
                // Calculate the distance between the two points
                var distance = BLLocation.distanceTo(myLocation);

                // Compare the distance with the radius (140 meters in this case) berada dalam jangkauan
                if (distance >= {{$setting->radius}}) {
                  swal('Oooops...', 'Anda berada di luar radius lokasi', 'error');
                  $("#konfbutton").hide();
                  $("#content > footer > div > p").addClass('mt-10');
                } else {
                    $("#konfbutton").show();
                    $("#content > footer > div > p").removeClass('mt-10');
                }
            }

            $("#konfbutton").click(function(){
                var latitude = $("#lat").val();
                var longitude = $("#long").val();
                var lokasi = $("#lokasi").val();

                $.ajax({
                    url: "{{route('mobile.api.absenmasuk')}}",
                    type: "POST",
                    data: {latitude, longitude, lokasi},
                    success: function(data) {
                        return swal(data.title, data.message, data.status).then(() => window.location = "{{route('mobile.views.home')}}");
                    }
                })
            });
        })
    </script>
@endsection
