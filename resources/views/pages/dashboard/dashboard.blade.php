<x-default-layout>
@section('title')
    Dashboard
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card p-4">
            <div class="card-body d-flex align-items-center">
                <div class="col-md-6 d-flex">
                    <!-- Foto Profil -->
                    <div class="symbol symbol-150px me-5">
                        <div class="symbol-label fs-3">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-2">{{ Auth::user()->name }}</h5>
                            <p class="text-muted m-0">
                                <i class="fas fa-user-shield"></i> {{ Auth::user()->type }}
                            </p>
                            <p class="text-muted">
                                <i class="fas fa-envelope"></i> {{ Auth::user()->email }}
                            </p>
                            <p class="text-muted m-0">
                                @if($cekAbsen)
                                    <span class="badge badge-light-success">Anda Sudah Absen Hari Ini</span>
                                @else
                                    <span class="badge badge-light-danger">Anda Belum Absen Hari Ini</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-center">
                        <h3 class="fw-bold" id="currentDate"></h3>
                        <h1 class="fw-bold" id="clock"></h1>
                    </div>
                    <div class="row mt-10">
                        <div class="col-sm-12 d-flex justify-content-center gap-2">
                            <button onclick="getLocation()" class="btn btn-primary btn-sm w-25"  @if($cekAbsen) disabled @endif >Absen Masuk</button>
                            <a class="button-ajax btn btn-danger btn-sm w-25" data-action="{{ route('logout') }}" data-method="post" data-csrf="{{ csrf_token() }}" data-reload="true">Absen Keluar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header">
                <div class="card-title">Cuti Yang Anda Ajukan</div>
            </div>
            <div class="card-body">
            <table class="table table-striped table-hover table-responsive" id="cutiTable" >
                    <thead>
                        <tr>
                            <th class="fw-bold text-center">#</th>
                            <th class="fw-bold text-start">Tanggal Pengajuan</th>
                            <th class="fw-bold text-start">Tanggal Cuti</th>
                            <th class="fw-bold text-start">Tanggal Selesai Cuti</th>
                            <th class="fw-bold text-start">Alasan Cuti</th>
                            <th class="fw-bold text-start">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataCuti as $cuti)
                        <tr>
                            <th class="text-center">{{$loop->iteration}}</th>
                            <td class="text-start">{{$cuti->created_at->format('Y-m-d')}}</td>
                            <td class="text-start">{{$cuti->tanggal_cuti}}</td>
                            <td class="text-start">{{$cuti->tanggal_cuti_selesai}}</td>
                            <td class="text-start">{{$cuti->alasan_cuti}}</td>
                            <td class="text-start">
                                @if ($cuti->status_permohonan == null)
                                    <span class="badge badge-light-info">Pending</span>
                                @elseif($cuti->status_permohonan)
                                    <span class="badge badge-light-success">Disetujui</span>
                                @else
                                    <span class="badge badge-light-info">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    setInterval(updateClock, 1000);
    updateDate();

    $("#cutiTable").DataTable({
    	"language": {
    		"lengthMenu": "Show _MENU_",
    	},
    	"dom":
    		"<'row mb-2'" +
    		"<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l>" +
    		"<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'f>" +
    		">" +

    		"<'table-responsive'tr>" +

    		"<'row'" +
    		"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
    		"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
    		">"
    });

    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: false });
        document.getElementById('clock').innerText = timeString;
    }

    function updateDate() {
        const today = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').innerText = today.toLocaleDateString('en-US', options);
    }

    function getLocation() {
        if ("geolocation" in navigator) { // Cek apakah browser mendukung Geolocation
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Jika user mengizinkan lokasi
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;

                    // Kirim data ke backend dengan AJAX
                    $.ajax({
                        url: "{{ route('log-absen.goAbsen') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF token Laravel
                            latitude: latitude,
                            longitude: longitude
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Absen Berhasil!",
                                text: "Anda sekarang sedang absen",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                location.reload(); // Reload halaman setelah absen berhasil
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: "Absen Gagal!",
                                text: "Terjadi kesalahan saat melakukan absen.",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                },
                function(error) {
                    // Jika user menolak izin lokasi atau terjadi error
                    Swal.fire({
                        title: "Izin Lokasi Diperlukan!",
                        text: "Anda harus mengizinkan akses lokasi untuk bisa melakukan absen.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                }
            );
        } else {
            Swal.fire({
                title: "Geolocation Tidak Didukung",
                text: "Browser Anda tidak mendukung fitur lokasi.",
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    }

</script>
@endpush
</x-default-layout>
