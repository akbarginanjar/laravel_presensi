<x-default-layout>
@section('title')
    Detail Absen Karyawan
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('log-absen-detail', $logAbsen) }}
@endsection


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    Data Detail Absen
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 d-flex gap-3">
                        <div class="col-sm-6">
                            <label class="form-label form-label">Nama Karyawan</label>
                            <input disabled class="form-control" value="{{ $logAbsen->user->name}}"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label form-label">Tanggal Absen</label>
                            <input disabled class="form-control" value="{{ $logAbsen->clock_in ? \Carbon\Carbon::parse($logAbsen->clock_in)->format('Y-m-d') : '-' }}"/>
                        </div>
                    </div>
                    <div class="col-sm-12 d-flex gap-3 mt-5">
                        <div class="col-sm-6">
                            <label class="form-label form-label">Waktu Masuk</label>
                            <input disabled class="form-control" value="{{ $logAbsen->clock_in ? \Carbon\Carbon::parse($logAbsen->clock_in)->format('H:i:s') : '-' }}"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label form-label">Waktu Keluar</label>
                            <input disabled class="form-control" value="{{ $logAbsen->clock_out ? \Carbon\Carbon::parse($logAbsen->clock_out)->format('H:i:s') : '-' }}"/>
                        </div>
                    </div>
                    @if($logAbsen->status_absen == 'izin')
                    <div class="col-sm-12 d-flex gap-3 mt-5">
                        <div class="col-sm-6">
                            <label class="form-label form-label">Jenis Izin</label>
                            <input disabled class="form-control" value="{{ $logAbsen->jenis_izin}}"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label form-label">Alasan Izin</label>
                            <textarea disabled class="form-control">{{ $logAbsen->alasan_izin}}</textarea>
                        </div>
                    </div>
                    @if($logAbsen->jenis_izin == 'Sakit')
                    <div class="col-sm-12 gap-3 mt-5">
                        <label class="form-label form-label">Bukti Izin</label>
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('storage/' . $logAbsen->bukti_izin) }}" alt="Bukti Izin Sakit" class="img-fluid mt-2 w-25">
                        </div>
                    </div>
                    @endif
                    @endif
                    @if($logAbsen->status_absen === 'tepat waktu' || $logAbsen->status_absen === 'telat')
                    <div class="col-md-12 mt-5">
                        <label class="form-label form-label">Lokasi Absen</label>
                        <div id="map" style="height:300px;"></div>
                    </div>
                    @endif
                </div>
            </div> 
        </div>
    </div>
</div>

@push('scripts')
<script>
     $(document).ready(function() {

        if (typeof L === 'undefined') {
            console.error("Leaflet.js tidak ditemukan. Pastikan file leaflet.bundle.js dimuat dengan benar.");
            return;
        }

        var mapOptions = {
            center: [-2.5489, 118.0149],
            zoom: 10
        };

        // Membuat peta dengan Leaflet
        var map = L.map('map', mapOptions);

        // Menambahkan layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var logAbsenId = "{{ $logAbsen->id }}";
        
        // Ambil data lokasi dari database melalui AJAX
        $.ajax({
            url: "{{ url('/log-absen/getLocation') }}/" + logAbsenId,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.latitude && response.longitude) {
                    L.marker([response.latitude, response.longitude])
                        .addTo(map);
                    
                    map.setView([response.latitude, response.longitude], 15);
                } else {
                    console.warn("Data lokasi tidak ditemukan!");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error mengambil data lokasi: " + error);
            }
        });
    });
</script>
@endpush
</x-default-layout>
