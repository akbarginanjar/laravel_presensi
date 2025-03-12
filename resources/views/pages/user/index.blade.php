<x-default-layout>
@section('title')
    Karyawan
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('user-management') }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        Data Karyawan
                    </div>
                    <div>
                        <a href="{{ route('user-management.create') }}" class="btn btn-primary btn-sm">+ Tambah Data</a>
                    </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover table-responsive" id="userTable" >
                    <thead>
                        <tr>
                            <th class="fw-bold">#</th>
                            <th class="fw-bold">Nama</th>
                            <th class="fw-bold">Email</th>
                            <th class="fw-bold">Type</th>
                            <th class="fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user as $user)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->type}}</td>
                            <td>
                                <a href="{{ route('user-management.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                {!! getIcon('notepad-edit', 'fs-2 text-light') !!}
                                </a> 
                                @if($user->type == 'Karyawan')
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}">
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

@push('scripts')
<script>
    $(document).ready(function () {
        
        $("#userTable").DataTable({
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
            let userId = $(this).data("id"); // Ambil ID dari data-id

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
                        url: "{{ url('/user-management/destroy') }}/" + userId,
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
    });
</script>
@endpush


</x-default-layout>
