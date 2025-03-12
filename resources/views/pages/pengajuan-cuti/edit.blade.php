<form id="cutiEditForm" action="{{ route('pengajuan-cuti.update', $data->id) }}" method="POST">
    <div class="modal-body">
        @csrf
        <div class="mt-4">
            <label for="" class="form-label fw-bold required">Tanggal Cuti</label>
            <input class="form-control form-control-solid datePicker" name="tanggal_cuti" value="{{ $data->tanggal_cuti }}"placeholder="Pilih Tanggal"/>
        </div>
        <div class="mt-4">
            <label for="" class="form-label fw-bold required">Tanggal Selesai Cuti</label>
            <input class="form-control form-control-solid datePicker" name="tanggal_selesai_cuti" value="{{$data->tanggal_cuti_selesai}}" placeholder="Pilih Tanggal Selesai Cuti"/>
        </div>
        <div class="mt-4">
            <label for="" class="form-label fw-bold required">Alasan Cuti</label>
            <textarea class="form-control form-control" name="alasan_cuti"> {{$data->alasan_cuti}} </textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button id="submitBtnEdit" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>