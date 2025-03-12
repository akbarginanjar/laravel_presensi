<x-default-layout>
@section('title')
    Karyawan
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('user-management-edit', $user) }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <form class="form" action="{{ route('user-management.update', $user->id)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            Edit Data Karyawan {{$user->name}}
                        </div>
                </div>
                <div class="card-body">
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Email</label>
	                	<input type="text" class="form-control" name="email" placeholder="Masukan email" value="{{ $user->email}}"/>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Nama Karyawan</label>
	                	<input type="text" class="form-control" name="name" placeholder="Masukan Nama Karyawan" value="{{ $user->name}}"/>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label form-label">Reset Password</label>
	                	<input type="password" id="passwordInput" class="form-control" disabled name="password" placeholder="Reset Password"/>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Tipe</label>
                        <select class="form-select" name="type" aria-label="Select example">
                            <option disabled>Pilih Tipe</option>
                            <option value="Admin" {{ $user->type == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Karyawan" {{ $user->type == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                        </select>
	                </div>
                    <div class="form-group">
	                	<label class="col-form-label required form-label">Departemen</label>
                        <select class="form-select" name="departemen" data-control="select2" data-placeholder="Pilih Departemen">
                                <option></option>
                            @foreach($departemen as $departemen)
                                <option value="{{$departemen->id}}" {{ $user->id_departemen == $departemen->id ? 'selected' : '' }}>{{ $departemen->nama_departemen}}</option>
                            @endforeach
                        </select>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Jabatan</label>
                        <select class="form-select" name="jabatan" data-control="select2" data-placeholder="Pilih Jabatan">
                                <option></option>
                            @foreach($jabatan as $jabatan)
                                <option value="{{$jabatan->id}}" {{ $user->id_jabatan == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan}}</option>
                            @endforeach
                        </select>
	                </div>
                </div> 
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    <button class="btn btn-light-warning btn-sm" id="togglePasswordReset" >Reset Password</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function () {
    $("#togglePasswordReset").on("click", function (event) {
            event.preventDefault();

            let passwordInput = $("#passwordInput");

            if (passwordInput.prop("disabled")) {
                passwordInput.prop("disabled", false).focus(); // Aktifkan & fokus input
                $(this).text("Batal Reset").removeClass("btn-light-warning").addClass("btn-light-danger"); // Ubah teks & warna tombol
            } else {
                passwordInput.prop("disabled", true).val(""); // Nonaktifkan & kosongkan input
                $(this).text("Reset Password").removeClass("btn-light-danger").addClass("btn-light-warning"); // Kembalikan teks & warna tombol
            }
        });
    });
</script>
@endpush
</x-default-layout>
