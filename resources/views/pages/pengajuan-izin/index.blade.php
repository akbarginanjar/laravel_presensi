<x-default-layout>
@section('title')
    Data Izin
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('data-izin-karyawan', $id) }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="card-title">Izin Saya</span> 
                </div>
                <div>
                    <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createIzin">+ Tambah Izin</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover table-responsive" id="dataIzin">
                    <thead>
                        <tr>
                            <th class="fw-bold">#</th>
                            <th class="fw-bold">Nama</th>
                            <th class="fw-bold text-start">Status</th>
                            <th class="fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $izin)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$izin->user->name}}</td>
                            <td class="text-start">
                                @if ($izin->status_absen == 'izin')
                                    <span class="badge badge-light-info">Izin</span>
                                @endif
                            </td>
                            <td>
                            <a class="btn btn-sm btn-warning btnDetailIzin" data-id="{{ $izin->id }}">
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
                        <select class="form-select" name="jenis_izin" id="jenisIzin" data-control="select2" data-dropdown-parent="#createIzin" data-hide-search="true" data-placeholder="Pilih Izin">
                            <option value="Sakit">Sakit</option>
                            <option value="izin">Izin Lainnya</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <label for="" class="form-label fw-bold required">Karyawan</label>
                        <select class="form-select" name="user_id" readonly data-dropdown-parent="#createIzin" data-hide-search="true" data-control="select2">
                            <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}</option>
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

<div class="modal fade" tabindex="-1" id="detailIzin">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Izin</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div id="response-detail-izin"></div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    $(document).ready(function(){

        $('.btnDetailIzin').click(function() {
            let izinId = $(this).data("id");
            $.ajax({
                type: "GET",
                url: "{{ route('log-absen.detailIzinKaryawan', '') }}" + '/' + izinId,
                success: function(response) {
                    $('#response-detail-izin').html(response);
                    $('#detailIzin').modal('show');
                }
            });
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

        $('#createIzin').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $('#submitBtn').click(function() {
            $('#izinForm').submit();
        });

        $("#dataIzin").DataTable({
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
