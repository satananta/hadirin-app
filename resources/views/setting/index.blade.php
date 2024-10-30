@extends('layouts.dashboard')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            position: relative;
            border: 1px solid black;
            border-radius: 8px;
            height: 100%;
            /* or as desired */
            width: 100%;
            /* This means "100% of the width of its container", the .col-md-8 */
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Setting</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Setting</li>
        </ol>
        <div class="row">
            <div class="col-md-6">
                @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{session()->get('success')}}
                </div>
                @endif
                <form action="{{route('admin.setting.update', '1')}}" method="post">
                    @method('put')
                    @csrf

                    <div class="from-group mb-3">
                        <label for="jam_masuk">Jam Absen</label>
                        <input type="time" name="jam_masuk" id="jam_masuk" class="form-control"
                            value="{{ $settings->jam_masuk }}" />
                    </div>
                    <div class="from-group mb-3">
                        <label for="jam_keluar">Jam Pulang</label>
                        <input type="time" name="jam_keluar" id="jam_keluar" class="form-control"
                            value="{{ $settings->jam_keluar }}" />
                    </div>
                    <div class="from-group mb-3">
                        <label for="max_jam_masuk">Jam Maksimal Absen</label>
                        <input type="time" name="max_jam_masuk" id="max_jam_masuk" class="form-control"
                            value="{{ $settings->max_jam_masuk }}" />
                    </div>
                    <div class="from-group mb-3">
                        <label for="lat">Latitute</label>
                        <input type="text" name="lat" id="lat" class="form-control" value="{{ $settings->lat }}">
                    </div>
                    <div class="from-group mb-3">
                        <label for="long">Longitude</label>
                        <input type="text" name="long" id="long" class="form-control" value="{{ $settings->long }}">
                    </div>
                    <div class="from-group mb-3">
                        <label for="radius">Radius (per meter) </label>
                        <input type="text" name="radius" id="radius" class="form-control"
                            value="{{ $settings->radius }}">
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Simpan</button>
                </form>
            </div>
            <div class="col-md-6">
                <div id="map"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let lat = {{ $settings->lat }};
            let long = {{ $settings->long }};
            let radius = {{ $settings->radius }};
            let codinate = [lat, long];
            let map;

            // Function to update the map
            function updateMap() {
                lat = $("#lat").val();
                long = $("#long").val();
                radius = $("#radius").val();

                codinate = [lat, long];

                // Remove existing layers from the map
                map.eachLayer(function(layer) {
                    map.removeLayer(layer);
                });

                // Add updated layers to the map
                map.setView(codinate, 17);
                L.circle(codinate, {radius}).addTo(map);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            }

            // Initial map setup
            map = L.map('map').setView(codinate, 17);
            L.circle(codinate, {
                radius: radius
            }).addTo(map);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            // Event listeners for input fields
            $("#lat, #long, #radius").on('input', function() {
                updateMap();
            });
        });
    </script>
@endsection
