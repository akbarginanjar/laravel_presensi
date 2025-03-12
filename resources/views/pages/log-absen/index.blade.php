<x-default-layout>
@section('title')
    Log Absen Karyawan
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('log-absen-index') }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header col-sm-12 d-flex justify-content-between align-items-center">
                <div class="col-sm-3">
                    <span class="card-title">Log Absen Karyawan</span> 
                </div>
                <div class="col-sm-9 d-flex align-items-center justify-content-end gap-3">
                    <input class="form-control w-25 form-control-solid datePickerRange" placeholder="Pilih Tanggal"/>
                    <button id="printLogAbsen" class="btn btn-primary btn-sm d-flex align-items-center gap-3">{!! getIcon('file-down', 'fs-2 text-light') !!} Laporan Mingguan</button>
                    <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createIzin">+ Tambah Izin</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover table-responsive" id="logAbsen">
                    <thead>
                        <tr>
                            <th class="fw-bold">#</th>
                            <th class="fw-bold">Nama Karyawan</th>
                            <th class="fw-bold text-start">Tanggal</th>
                            <th class="fw-bold">Waktu Masuk</th>
                            <th class="fw-bold">Waktu Keluar</th>
                            <th class="fw-bold text-start">Status</th>
                            <th class="fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logAbsen as $logAbsen)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$logAbsen->user->name}}</td>
                            <td class="text-start">{{$logAbsen->clock_in ? \Carbon\Carbon::parse($logAbsen->clock_in)->format('Y-m-d') : '-' }}</td>
                            <td>{{$logAbsen->clock_in ? \Carbon\Carbon::parse($logAbsen->clock_in)->format('H:i:s') : '-'}}</td>
                            <td>{{$logAbsen->clock_out ? \Carbon\Carbon::parse($logAbsen->clock_out)->format('H:i:s') : '-' }}</td>
                            <td class="text-start">
                                @if ($logAbsen->status_absen == 'tepat waktu')
                                    <span class="badge badge-light-success">Sudah Absen</span>
                                @elseif ($logAbsen->status_absen == 'telat')
                                    <span class="badge badge-light-danger">Telat</span>
                                @else
                                    <span class="badge badge-light-info">Izin</span>
                                @endif
                            </td>
                            <td>
                            <a href="{{ route('log-absen.detail', $logAbsen->id) }}" class="btn btn-sm btn-warning">
                                {!! getIcon('right-square', 'fs-2 text-light') !!}
                                </a> 
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="createIzin">
    <div class="modal-dialog  modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambahkan Izin</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body">
                <form id="izinForm" action="{{ route('log-absen.createIzin') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div>
                        <label for="" class="form-label fw-bold required">Jenis Izin</label>
                        <select class="form-select" name="jenis_izin" id="jenisIzin" data-control="select2" data-dropdown-parent="#createIzin" data-hide-search="true" data-placeholder="Pilih Jenis Izin">
                            <option value="Sakit">Sakit</option>
                            <option value="izin">Izin Lainnya</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <label for="" class="form-label fw-bold required">Karyawan</label>
                        <select class="form-select" name="user_id" data-dropdown-parent="#createIzin" data-control="select2" data-placeholder="Pilih Karyawan">
                                <option></option>
                            @foreach($user as $users)
                                <option value="{{$users->id}}">{{ $users->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4" id="buktiIzin" style="display: none;">
                        <label for="" class="form-label fw-bold required">Bukti Izin Sakit</label>
                        <input type="file" class="form-control" name="bukti_izin" accept=".png, .jpeg, jpg" required>
                    </div>
                    <div class="mt-4">
                        <label for="" class="form-label fw-bold required">Alasan Izin</label>
                        <textarea class="form-control form-control" name="alasan_izin"> </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button id="submitBtn" type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){

        $(".datePickerRange").flatpickr({
            dateFormat: "Y-m-d",
            mode: "range"
        });

        $('#createIzin').on('shown.bs.modal', function() {
            $('#jenisIzin').val('Sakit').trigger('change');
            $('#buktiIzin').show(); // Pastikan ditampilkan langsung
        });

        $('#jenisIzin').on('change', function() {
            if ($(this).val() === 'Sakit') {
                $('#buktiIzin input').attr('name', 'bukti_izin');
                $('#buktiIzin').show();
            } else {
                $('#buktiIzin input').removeAttr('name');
                $('#buktiIzin').hide();
            }
        });

        $('#printLogAbsen').on('click', function() {
            var tanggalRange = $(".datePickerRange").val();
            
            $.ajax({
                url: "{{ route('log-absen.printLaporan')}}",
                type: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal_eksport: tanggalRange,
                }, success: function(response) {
                    if(response.success) {
                        var newWindow = window.open('', '_blank');
                        newWindow.document.write(response.html);
                        newWindow.document.close();
                    }
                }
            });
        });

        $('#createIzin').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $('#submitBtn').click(function() {
            $('#izinForm').submit();
        });

        $("#logAbsen").DataTable({
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
    });
</script>
@endpush
</x-default-layout>
