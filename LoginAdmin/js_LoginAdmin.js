// Toggle Show / Hide Password
const toggleBtn = document.getElementById('togglePass');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');

toggleBtn.addEventListener('click', () => {
  // Sembunyikan password
  const isHidden = passwordInput.type === 'password';
  // Ganti tipe input
  passwordInput.type = isHidden ? 'text' : 'password';
  // Ganti ikon mata
  eyeIcon.className = isHidden ? 'bi bi-eye' : 'bi bi-eye-slash';
});

// Validasi Form
const form            = document.getElementById('formLogin');
const nipInput        = document.getElementById('nip');
const errorNip        = document.getElementById('errorNip');
const errorPassword   = document.getElementById('errorPassword');
const wrapperNip      = document.getElementById('wrapperNip');
const wrapperPassword = document.getElementById('wrapperPassword');
  
function setInvalid(wrapper, msgEl, msg, errorIcon) {
  wrapper.classList.add('is-invalid');
  msgEl.innerHTML = `<i class="bi bi-exclamation-circle me-1"></i>${msg}`;
  if (errorIcon) errorIcon.style.display = 'inline-block';
}

function resetField(wrapper, msgEl, showLeftIcon) {
  wrapper.classList.remove('is-invalid');
  msgEl.innerHTML = '';
  if (showLeftIcon) showLeftIcon.style.display = 'none';
}

form.addEventListener('submit', function (e) {
  e.preventDefault();

  let valid = true;

  // Reset semua
  resetField(wrapperNip, errorNip, null);
  resetField(wrapperPassword, errorPassword, null);

  // Validasi NIP
  if (nipInput.value.trim() === '') {
    setInvalid(wrapperNip, errorNip, 'NIP tidak boleh kosong.', null);
    valid = false;
  }

  // Validasi Password
  if (passwordInput.value.trim() === '') {
    setInvalid(wrapperPassword, errorPassword, 'Password tidak boleh kosong.', null);
    valid = false;
  } else if (passwordInput.value.trim().length < 8) {
    setInvalid(wrapperPassword, errorPassword, 'Password minimal 8 karakter.', null);
    valid = false;
  }

  if (valid) {
    console.log('Login Berhasil');
  }
});

// Reset error saat user mulai mengetik
nipInput.addEventListener('input', () => resetField(wrapperNip, errorNip, null));
passwordInput.addEventListener('input', () => resetField(wrapperPassword, errorPassword, null));