 <!-- ================================================================
       MODAL: KONFIRMASI LOGOUT
  ================================================================ -->
  <div class="modal fade" id="modalLogout" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
      <div class="modal-content">
        <div class="modal-header" style="background:#fd7e14;">
          <h6 class="modal-title fw-bold text-white">Logout Karyawan</h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center py-4">
          <div class="fs-1 mb-2">↩</div>
          <p class="mb-1">Apakah Anda yakin ingin menglogout karyawan ini?</p>
          <p class="fw-bold" id="logout_nama">-</p>
          <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
          <input type="hidden" id="logout_id" />
        </div>
        <div class="modal-footer justify-content-center">
          <form action="<?= BASE_URL . 'karyawan/logout' ?>" method="post">
            <input type="hidden" name="user_id" id="input_user_id_logout">

            <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-modal-logout1 px-4" type="submit" id="btn-konfirmasi-logout">Logout</button>
          </form>


        </div>
      </div>
    </div>
  </div>