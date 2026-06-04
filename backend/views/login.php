<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= APP_NAME ?> - Login Admin</title>

  <!-- Bootstrap -->
  <link href="<?= pathCss('bootstrap') ?>" rel="stylesheet" >
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= pathCss('login') ?>"/>
</head>
<body>

  <div class="container-fluid p-0">
    <div class="row g-0">

      <!-- PANEL KIRI -->
      <div class="col-12 col-md-7 left-panel d-flex flex-column justify-content-between p-5">

        <!-- Brand Badge -->
        <div>
          <div class="brand-badge">
            <i class="bi bi-building"></i>
            <span><?= APP_NAME ?></span>
          </div>
        </div>

        <!-- Hero Text -->
        <div class="pb-3">
          <h1 class="hero-title mb-3 ml2">Selamat Datang</h1>
          <p class="hero-sub mb-0 ml3">
            Catat kehadiran hanya dengan <strong>sekali pindai</strong>
          </p>
        </div>

        <!-- Spacer -->
        <div></div>
      </div>

      <!-- PANEL KANAN -->
      <div class="col-12 col-md-5 right-panel">
        <div class="login-card">

          <!-- Alert Error -->
          <?php displayFlashMessage(); ?>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= e($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          
          <h2 class="login-title mb-1">Login Sekarang</h2>
          <p class="login-sub mb-5">Isi data di bawah ini untuk melanjutkan</p>

          <!-- FORM LOGIN -->
          <form id="formLogin" action="<?= BASE_URL . 'login' ?>" method="post">

            <!-- Username -->
            <div class="mb-3">
              <div class="input-group-custom" id="wrapperNip">
                <i class="bi bi-person-fill input-icon"></i>
                <input
                type="text"
                class="form-control"
                id="username"
                placeholder="Masukkan username"
                value="<?= e($_POST['username'] ?? '') ?>"
                name="username"
                autocomplete="username"
                />
              </div>
              <small id="errorNip" class="text-danger ms-1 d-flex align-items-center gap-1"></small>
            </div>

            <!-- Password -->
            <div class="mb-2">
              <div class="input-group-custom" id="wrapperPassword">
                <i class="bi bi-lock-fill input-icon icon-lock"></i>
                <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                value="<?= e($_POST['password'] ?? '') ?>"
                placeholder="Masukkan Password"
                autocomplete="current-password"
                />
                <button type="button" class="toggle-password" id="togglePass" tabindex="-1">
                  <i class="bi bi-eye-slash" id="eyeIcon"></i>
                </button>
              </div>
              <small id="errorPassword" class="text-danger ms-1"></small>
            </div>

            <!-- Ingat Saya -->
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="rememberMe"/>
              <label class="form-check-label"  id="remember" name="remember" for="rememberMe">Ingat Saya</label>
            </div>

            <!-- Masuk -->
            <button type="submit" class="btn btn-masuk w-100">
              Masuk
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script src="<?= pathJs('anime.min') ?>"></script>
  <!-- Bootstrap JS -->
  <script src="<?= pathJs('bootstrap') ?>"></script>
  <!-- Custom JS -->
  <script src="<?= pathJs('login') ?>"></script>
  

</body>
</html>