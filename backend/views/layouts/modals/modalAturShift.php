
<!-- ════════════════════════════════════════
     MODAL ATUR SHIFT
════════════════════════════════════════ -->
<div class="modal fade" id="modalAturShift" tabindex="-1"
     aria-labelledby="modalAturShiftLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-custom">

      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="modalAturShiftLabel">Ubah Shift Karyawan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Info karyawan -->
        <div class="modal-karyawan-info" id="modal-karyawan-info">
          <div class="modal-karyawan-avatar" id="modal-avatar">–</div>
          <div>
            <div class="modal-karyawan-nama" id="modal-nama">–</div>
            <div class="modal-karyawan-meta" id="modal-meta">–</div>
          </div>
        </div>

        <!-- Shift saat ini -->
        <div class="modal-shift-current">
          <div>
            <div class="modal-shift-current-label">Shift Saat Ini</div>
            <div style="font-weight:700;font-size:.9rem;margin-top:3px;" id="modal-shift-sekarang">–</div>
          </div>
          <div id="modal-shift-sekarang-chip"></div>
        </div>

        <!-- Alert terkunci -->
        <div class="alert-locked d-none" id="modal-alert-locked">
          <i class="bi bi-lock-fill"></i>
          Shift tidak dapat diubah karena karyawan sedang aktif shift hari ini (belum absen pulang).
        </div>

        <!-- Pilihan shift baru -->
        <div style="font-size:.78rem;font-weight:700;color:var(--muted);
                    text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">
          Pilih Shift Baru
        </div>
        <div class="shift-option-grid" id="shift-option-grid">
          <!-- Diisi dinamis -->
        </div>

      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn-modal-batal"
                data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn-modal-simpan"
                id="btnSimpanShift" disabled>
          <i class="bi bi-check-lg me-1"></i> Simpan Shift
        </button>
      </div>

    </div>
  </div>
</div>