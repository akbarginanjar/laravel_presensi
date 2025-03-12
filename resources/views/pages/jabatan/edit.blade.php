<form action="{{ route('jabatan.update', $data->id) }}" method="POST">
    <div class="modal-body">
        @csrf
        <div class="mt-4">
            <label for="" class="form-label fw-bold required">Nama Jabatan</label>
            <input class="form-control form-control-solid datePicker" name="nama_jabatan" value="{{ $data->nama_jabatan }}" placeholder="Nama Jabatan"/>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button id="submitBtnEdit" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>