// -- Toggle Show / Hide Password --
const toggleBtn = document.getElementById('togglePass');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');

toggleBtn.addEventListener('click', () => {
  const isHidden = passwordInput.type === 'password';

  // Ganti tipe input
  passwordInput.type = isHidden ? 'text' : 'password';

  // Ganti ikon mata
  eyeIcon.className = isHidden ? 'bi bi-eye' : 'bi bi-eye-slash';
});