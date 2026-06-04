<!-- Custom CSS -->
<link rel="stylesheet" href="<?= pathCss('karyawan'); ?>"/>
<link rel="stylesheet" href="<?= pathCss('kCreate'); ?>"/>


<!-- ── Section 1: Header + Filter + Stats ── -->
<section class="karyawan-header-section mb-4">

  <!-- Judul -->
  <h4 class="page-title">Data Karyawan</h4>
  <p class="page-subtitle">Informasi Seluruh Karyawan Aktif</p>

  <!-- Toolbar: Filter Divisi, Filter Status, Tombol Tambah -->
  <div class="toolbar d-flex flex-wrap align-items-center gap-2 mt-3 mb-4">

  </section>

  <section class="karyawan-header-section mb-4">
    <?= \headerError('general') ?>
    <div class="page-card-title">Tambah Karyawan Baru</div>
    <div class="page-card-subtitle">Isi semua data karyawan dengan lengkap dan benar</div>


    <form action="<?= BASE_URL . 'karyawan/create' ?>" method="post">
      <input type="hidden" name="_token" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label-custom" for="formNama">Nama Lengkap</label>
          <input type="text" class="form-input-custom <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" 
          name="nama_lengkap" 
          id="formNama" 
          value="<?= e($old['nama_lengkap'] ?? '') ?>"
          placeholder="Masukkan nama lengkap"/>
          <?= \messageError('nama_lengkap') ?>
        </div>

        <div class="col-md-6">
          <label class="form-label-custom" for="formDivisi">Divisi</label>
          <select class="form-input-custom" id="formDivisi" name="divisi">
            <option value="">-- Pilih Divisi --</option>

            <?php foreach($divisis as $divisi) { ?>   
              <option value='<?= $divisi["divisi_id"] ?>'><?= $divisi["nama_divisi"] ?></option>
            <?php } ?>

          </select>

          <?= \messageError('divisi') ?>
        </div>

        <div class="col-md-6">
          <label class="form-label-custom" for="formJabatan">Jabatan</label>
          <select class="form-input-custom" id="formJabatan" name="jabatan">
            <option value="">-- Pilih Jabatan --</option>
          </select>

          <?= \messageError('jabatan') ?>
        </div>

        <div class="col-md-6">
          <label class="form-label-custom" for="formHp">No. Handphone</label>
          <input type="text" class="form-input-custom" id="formHp" name="no_handphone" placeholder="Contoh: 0812-3456-7890"/ value="<?= e($old['no_handphone'] ?? '') ?>">

          <?= \messageError('no_handphone') ?>
        </div>

        <div class="col-md-6">
          <label class="form-label-custom" for="formEmail">Email</label>
          <input type="email" class="form-input-custom" name="email" id="formEmail" placeholder="nama@nafihaka.com" value="<?= e($old['email'] ?? '') ?>" />

          <?= \messageError('email') ?>
        </div>

        <div class="col-md-6">
          <label class="form-label-custom" for="formStatus">Status</label>
          <select class="form-input-custom" name="status" id="formStatus">
            <option value="aktif">Aktif</option>
            <option value="cuti">Cuti</option>
          </select>

          <?= \messageError('status') ?>
        </div>

        <div class="col-12">
          <label class="form-label-custom" for="formAlamat">Alamat</label>
          <textarea class="form-input-custom" name="alamat" id="formAlamat" rows="3" placeholder="Masukkan alamat lengkap" value="<?= e($old['alamat'] ?? '') ?>"></textarea>

          <?= \messageError('alamat') ?>
        </div>

      </div>

      <div id="form-error" class="form-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span id="form-error-msg"></span>
      </div>

      <hr class="divider"/>

      <div class="d-flex justify-content-end gap-2">
        <a type="button" class="btn-modal-batal" href="javascript:history.back()" style="text-decoration: none;">Batal</a>
        <button type="submit" class="btn-modal-simpan" id="btnSimpan">
          <i class="bi bi-floppy-fill"></i> Simpan
        </button>
      </div>

    </form>
  </section>



<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap.bundle.min') ?>"></script>
<script>
  let selectDivisi = document.getElementById('formDivisi');
  let selectJabatan = document.getElementById('formJabatan');

  selectDivisi.addEventListener('change', async function (e) {
    let options = {
      method: 'POST',
      headers: {
        'Content-type' : 'application/json'
      },
      body: JSON.stringify({
        divisi_id: selectDivisi.value
      }),
    }


    try {

      let response = await fetch('http://localhost:8080/presensi/api/komponen/showJabatan', options);
      let dataJabatan = await response.json();

      selectJabatan.innerHTML = "";

      dataJabatan.forEach(jabatan => {

        let option = document.createElement("option");

        option.value = jabatan.jabatan_id;
        option.textContent = jabatan.nama_jabatan;

        selectJabatan.appendChild(option);

      });

    } catch (Err) {
      console.log(Err);
    }


  });


</script>
