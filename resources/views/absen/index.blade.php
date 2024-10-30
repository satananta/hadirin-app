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
        <h1 class="mt-4">Absen</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Histori Absen</li>
        </ol>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar Karyawan Absen
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absens as $absen)
                                    <tr>
                                        <td>{{ $absen->user->nama }}</td>
                                        <td>{{ $absen->user->jabatan }}</td>
                                        <td>{{ substr($absen->jam_datang, 0, 5) }}</td>
                                        <td>{{ substr($absen->jam_pulang, 0, 5) }}</td>
                                        <td>{{ $absen->lokasi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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

            let markersData = [
                @foreach ($absens as $absen)
                    {
                        id: {{ $absen->user->id }},
                        lat: {{ $absen->lat }},
                        long: {{ $absen->long }},
                        name: "{{ $absen->user->nama }}",
                        photo: "{{ $absen->user->photo }}",
                        entryTime: "{{ substr($absen->jam_datang, 0, 5) }}",
                        exitTime: "{{ substr($absen->jam_pulang, 0, 5) }}",
                        location: "{{ $absen->lokasi }}"
                    },
                @endforeach
            ];
            
            // Create a dictionary to store the association between table rows and markers
            let markers = [];

            // Function to create Leaflet markers and associate with table rows
            function createMarkers() {
                markersData.forEach(function(data) {
                    let marker = L.marker([data.lat, data.long], {
                            title: data.name
                        })
                        .addTo(map)
                        .bindPopup(`
                        <div class="d-flex">
                            <img class="img-fluid w-25 h-25 mx-1" src="${data.photo !== "" ? `{{asset('assets/image/karyawan/${data.photo}')}}` : "{{asset('assets/image/pp.png')}}"}" />
                            <p><b>${data.name}</b></p>
                        </div>
                        <div class="mx-1">
                            <p>Jam Masuk: <b>${data.entryTime}</b></p>
                            <p>Jam Pulang: <b>${data.exitTime}</b></p>
                            <p>Lokasi: <b>${data.location}</b></p>
                        </div>
                    `);

                    markers.push(marker);
                });
            }

            // Initial map setup
            map = L.map('map').setView(codinate, 17);
            L.circle(codinate, {
                radius
            }).addTo(map);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            // Add markers to the map
            createMarkers();

            $('#datatablesSimple tbody tr').hover(
                function() {
                    let markerIndex = $(this).index();
                    let marker = markers[markerIndex];

                    if (marker) {
                        marker.openPopup();
                    }
                },
                function() {
                    let markerIndex = $(this).index();
                    let marker = markers[markerIndex];

                    if (marker) {
                        marker.closePopup();
                    }
                }
            );
        });
    </script>
@endsection
