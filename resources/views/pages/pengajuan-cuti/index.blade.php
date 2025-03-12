<x-default-layout>
@section('title')
    Pengajuan Cuti
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('pengajuan-cuti-index', $id) }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    Cuti Saya
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCuti">+ Tambah Cuti</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover table-fixed" id="cutiTable" >
                    <thead>
                        <tr>
                            <th class="fw-bold text-center">#</th>
                            <th class="fw-bold text-start">Tanggal Pengajuan</th>
                            <th class="fw-bold text-start">Tanggal Cuti</th>
                            <th class="fw-bold text-start">Tanggal Selesai Cuti</th>
                            <th class="fw-bold text-start">Status</th>
                            <th class="fw-bold text-start">Alasan Cuti</th>
                            <th class="fw-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $cuti)
                        <tr>
                            <th class="text-center">{{$loop->iteration}}</th>
                            <td class="text-start">{{$cuti->created_at->format('Y-m-d')}}</td>
                            <td class="text-start">{{$cuti->tanggal_cuti}}</td>
                            <td class="text-start">{{$cuti->tanggal_cuti_selesai}}</td>
                            <td class="text-start">
                                @if ($cuti->status_permohonan == null)
                                    <span class="badge badge-light-info">Pending</span>
                                @elseif($cuti->status_permohonan)
                                    <span class="badge badge-light-success">Disetujui</span>
                                @else
                                    <span class="badge badge-light-info">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-start">{{$cuti->alasan_cuti}}</td>
                            <td class="text-center">
                                @if ($cuti->status_permohonan == false)
                                <a href="javascript:void(0)" class="btn btn-sm btn-warning" id="btnEditCuti" data-id="{{ $cuti->id }}">
                                    {!! getIcon('notepad-edit', 'fs-2 text-light') !!}
                                </a> 
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-btn" data-id="{{ $cuti->id }}">
                                    {!! getIcon('trash', 'fs-2 text-light') !!}
                                </a>
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


<div class="modal fade" tabindex="-1" id="createCuti">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Permintaan Cuti</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body">
                <form id="cutiForm" action="{{ route('pengajuan-cuti.store') }}" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label for="" class="form-label fw-bold required">Tanggal Cuti</label>
                        <input class="form-control form-control-solid datePicker" name="tanggal_cuti" placeholder="Pilih Tanggal"/>
                    </div>
                    <div class="mt-4">
                        <label for="" class="form-label fw-bold required">Tanggal Selesai Cuti</label>
                        <input class="form-control form-control-solid datePicker" name="tanggal_selesai_cuti" placeholder="Pilih Tanggal Selesai Cuti"/>
                    </div>
                    <div class="mt-4">
                        <label for="" class="form-label fw-bold required">Alasan Cuti</label>
                        <textarea class="form-control form-control" name="alasan_cuti"> </textarea>
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

<div class="modal fade" tabindex="-1" id="editCuti">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Permintaan Cuti</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div id="response-edit-form"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){

        $('#btnEditCuti').click(function() {
            let cutiId = $(this).data("id");
            $.ajax({
                type: "GET",
                url: "{{ route('pengajuan-cuti.edit', '') }}" + '/' + cutiId,
                success: function(response) {
                    $('#response-edit-form').html(response);
                    $('#editCuti').find(".datePicker").flatpickr({
                        minDate: "today",
                        dateFormat: "Y-m-d",
                    });
                    $('#editCuti').modal('show');
                }
            });
        });
        
        $('#submitBtn').click(function() {
            $('#cutiForm').submit();
        });

        $('#createCuti').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $(".datePicker").flatpickr({
            minDate: "today",
            dateFormat: "Y-m-d",
        });

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

        $(".delete-btn").on("click", function () {
            let cutiId = $(this).data("id");

            Swal.fire({
                title: 'Hapus Data',
                text: "Apakah Anda yakin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pengajuan-cuti.destroy', '') }}" + '/' + cutiId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", "Data berhasil dihapus.", "success");
                            location.reload();
                        },
                        error: function (xhr) {
                            Swal.fire("Error!", "Gagal menghapus data.", "error");
                        }
                    });
                }
            });
        });

    })
</script>
@endpush
</x-default-layout>
