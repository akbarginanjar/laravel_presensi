<x-default-layout>
@section('title')
    Departemen
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('data-departemen') }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    Departemen
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createDepartemen">+ Tambah Departemen</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover table-fidepartemen" id="departemenTable" >
                    <thead>
                        <tr>
                            <th class="fw-bold text-center">#</th>
                            <th class="fw-bold text-start">Nama Departemen</th>
                            <th class="fw-bold text-start">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $departemen)
                        <tr>
                            <th class="text-center">{{$loop->iteration}}</th>
                            <td class="text-start">{{$departemen->nama_departemen}}</td>
                            <td class="text-start">
                                <a href="javascript:void(0)" class="btn btn-sm btn-warning" id="btnEditDepartemen" data-id="{{ $departemen->id }}">
                                    {!! getIcon('notepad-edit', 'fs-2 text-light') !!}
                                </a> 
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-btn" data-id="{{ $departemen->id }}">
                                    {!! getIcon('trash', 'fs-2 text-light') !!}
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


<div class="modal fade" tabindex="-1" id="createDepartemen">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Departemen</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body">
                <form id="departemenForm" action="{{ route('departemen.store') }}" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label for="" class="form-label fw-bold required">Nama Departemen</label>
                        <input class="form-control form-control" name="nama_departemen" placeholder="Nama Departemen"/>
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

<div class="modal fade" tabindex="-1" id="departemenCuti">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Jabatan</h3>
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

        $('#btnEditDepartemen').click(function() {
            let departemenId = $(this).data("id");
            $.ajax({
                type: "GET",
                url: "{{ route('departemen.edit', '') }}" + '/' + departemenId,
                success: function(response) {
                    $('#response-edit-form').html(response);
                    $('#departemenCuti').modal('show');
                }
            });
        });
        
        $('#submitBtn').click(function() {
            $('#departemenForm').submit();
        });

        $('#createDepartemen').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });


        $("#departemenTable").DataTable({
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
            let departemenId = $(this).data("id");

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
                        url: "{{ route('departemen.destroy', '') }}" + '/' + departemenId,
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
