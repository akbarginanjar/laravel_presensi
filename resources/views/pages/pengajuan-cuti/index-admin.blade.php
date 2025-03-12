<x-default-layout>
@section('title')
    Pengajuan Cuti
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('data-cuti') }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    Data Cuti
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover table-responsive" id="cutiTable" >
                    <thead>
                        <tr>
                            <th class="fw-bold text-center">#</th>
                            <th class="fw-bold text-start">Nama Karyawan</th>
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
                            <td class="text-start">{{$cuti->user->name}}</td>
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
                                @if ($cuti->status_permohonan == null  )
                                <a href="javascript:void(0)" class="btn btn-sm btn-success btnCutiAction" data-id="{{ $cuti->id }}" data-action="approve">
                                    {!! getIcon('check-square', 'fs-2 text-light') !!}
                                </a> 
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger btnCutiAction" data-id="{{ $cuti->id }}" data-action="reject">
                                    {!! getIcon('cross-square', 'fs-2 text-light') !!}
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
    $(document).ready(function(){


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

        $(".btnCutiAction").click(function () {
        let cutiId = $(this).data("id");
        let action = $(this).data("action"); // 'approve' atau 'reject'
        let actionText = action === "approve" ? "Menyetujui" : "Menolak";

        Swal.fire({
            title: "Konfirmasi",
            text: `Apakah Anda yakin ingin ${actionText} permohonan cuti ini?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: action === "approve" ? "#28a745" : "#dc3545",
            cancelButtonColor: "#9e77f0",
            confirmButtonText: action === "approve" ? "Ya, Setujui!" : "Ya, Tolak!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('pengajuan-cuti.approval', '') }}" + '/' + cutiId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        action: action,
                    },
                    success: function (response) {
                        Swal.fire("Berhasil!", response.message, "success").then(() => {
                            location.reload(); // Reload halaman setelah aksi sukses
                        });
                    },
                    error: function (xhr) {
                        Swal.fire("Error!", "Terjadi kesalahan, coba lagi.", "error");
                    },
                });
            }
        });
    });

    })
</script>
@endpush
</x-default-layout>
