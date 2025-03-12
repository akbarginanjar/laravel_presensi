<x-default-layout>
@section('title')
    Karyawan
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('user-management-create') }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <form class="form" action="{{ route('user-management.store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            Tambah Data Karyawan
                        </div>
                </div>
                <div class="card-body">
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Email</label>
	                	<input type="text" class="form-control" name="email" placeholder="Masukan email"/>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Nama Karyawan</label>
	                	<input type="text" class="form-control" name="name" placeholder="Masukan Nama Karyawan"/>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Password</label>
	                	<input type="password" class="form-control" name="password" placeholder="Masukan Password"/>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Tipe</label>
	                	    <select class="form-select" name="type" aria-label="Select example">
                                <option>Pilih Tipe</option>
                                <option value="Admin">Admin</option>
                                <option value="Karyawan">Karyawan</option>
                            </select>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Departemen</label>
                        <select class="form-select" name="departemen" data-control="select2" data-placeholder="Pilih Departemen">
                                <option></option>
                            @foreach($departemen as $departemens)
                                <option value="{{$departemens->id}}">{{ $departemens->nama_departemen}}</option>
                            @endforeach
                        </select>
	                </div>
	                <div class="form-group">
	                	<label class="col-form-label required form-label">Jabatan</label>
                        <select class="form-select" name="jabatan" data-control="select2" data-placeholder="Pilih Jabatan">
                                <option></option>
                            @foreach($jabatan as $jabatan)
                                <option value="{{$jabatan->id}}">{{ $jabatan->nama_jabatan}}</option>
                            @endforeach
                        </select>
	                </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    <button type="reset" class="btn btn-light-primary btn-sm">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

</x-default-layout>
