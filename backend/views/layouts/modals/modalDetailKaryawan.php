  <!-- ═══════════════════════════════════════
       MODAL DETAIL KARYAWAN
  ════════════════════════════════════════ -->
  <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content modal-custom">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold" id="modalDetailLabel">Detail Informasi Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body" id="modal-detail-body">
          <!-- Diisi JS -->
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn-modal-logout" id="btnModalLogout">
            <i class="bi bi-box-arrow-right"></i> Logout
          </button>
          <button type="button" class="btn-modal-edit" id="btnModalEdit">
            <i class="bi bi-pencil-square"></i> Edit Data
          </button>
          <button type="button" class="btn-modal-hapus" id="btnModalHapus">
            <i class="bi bi-trash"></i> Hapus Data
          </button>
          <button type="button" class="btn-modal-tutup" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>