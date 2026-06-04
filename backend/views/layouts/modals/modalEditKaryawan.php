<style>
  .show {
    display: block;
  }
</style>


<!-- ═══════════════════════════════════════
       MODAL TAMBAH / EDIT KARYAWAN
  ════════════════════════════════════════ -->
<form action="<?= \BASE_URL . 'karyawan/update' ?>" method="post">

      <input type="hidden" name="_token" value="<?= $_SESSION['csrf_token'] ?>">
      <input type="hidden" class="form-input-custom " id="formuser_id" name="user_id" placeholder="Contoh: NFC-2026-001"/>


  <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content modal-custom">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold" id="modalFormLabel">Tambah Karyawan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label-custom">Nama Lengkap</label>
              <input type="text" class="form-input-custom <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" id="formNama" placeholder="Masukkan nama lengkap" name="nama_lengkap" />
              <?= \messageError('nama_lengkap') ?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Divisi</label>
              <select class="form-input-custom" id="formDivisi" name="divisi">
                <option value="">-- Pilih Divisi --</option>
              </select>
              <?= \messageError('divisi') ?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Jabatan</label>
              <select class="form-input-custom" id="formJabatan" name="jabatan">
                <option value="">-- Pilih Jabatan --</option>
              </select>
              <?= \messageError('jabatan') ?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">No. Handphone</label>
              <input type="text" class="form-input-custom" id="formHp" name="no_handphone" placeholder="Contoh: 0812-3456-7890"/>
              <?= \messageError('no_handphone') ?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Email</label>
              <input type="email" class="form-input-custom" id="formEmail" name="email" placeholder="nama@nafihaka.com"/>
              <?= \messageError('email') ?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Status</label>
              <select class="form-input-custom" id="formStatus" name="status">
                <option value="Aktif">Aktif</option>
                <option value="Cuti">Cuti</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Tanggal Bergabung</label>
              <input type="date" disabled class="form-input-custom" id="formTanggal"/>
            </div>
            <div class="col-12">
              <label class="form-label-custom">Alamat</label>
              <textarea class="form-input-custom" id="formAlamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
              <?= \messageError('alamat') ?>
            </div>
          </div>
          <div id="form-error" class="form-error d-none">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span id="form-error-msg"></span>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn-modal-simpan" id="btnSimpan">
            <i class="bi bi-floppy-fill"></i> Simpan
          </button>
        </div>
      </div>
    </div>
  </div>

</form>