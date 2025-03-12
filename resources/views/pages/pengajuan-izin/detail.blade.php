<div class="modal-body">
    <div>
        <label for="" class="form-label fw-bold required">Jenis Izin</label>
        <select class="form-select" name="jenis_izin" disabled data-control="select2" data-dropdown-parent="#createIzin" data-hide-search="true" data-placeholder="Pilih Izin">
            <option value="Sakit" {{ $data->jenis_izin == 'Sakit' ? 'selected' : '' }}>Sakit</option>
            <option value="izin" {{ $data->jenis_izin == 'izin' ? 'selected' : '' }}>Izin Lainnya</option>
        </select>
    </div>
    <div class="mt-4">
        <label for="" class="form-label fw-bold required">Karyawan</label>
        <select class="form-select" name="user_id" disabled  data-control="select2">
            <option selected>{{ $data->user->name }}</option>
        </select>
    </div>
    @if($data->jenis_izin == 'Sakit')
    <div class="mt-4" id="buktiIzin">
        <label for="" class="form-label fw-bold required">Bukti Izin Sakit</label>
        <div class="d-flex align-itemn-center justify-content-center">
            <img src="{{ asset('storage/' . $data->bukti_izin) }}" alt="Bukti Izin Sakit" class="img-fluid mt-2 w-50">
        </div>
    </div>
    @endif
    <div class="mt-4">
        <label for="" class="form-label fw-bold required">Alasan Izin</label>
        <textarea class="form-control form-control" disabled name="alasan_izin">{{ $data->alasan_izin}} </textarea>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
</div>