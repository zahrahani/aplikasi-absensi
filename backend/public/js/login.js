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

// Animasi Judul

var textWrapper = document.querySelector('.ml2');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: true})
  .add({
    targets: '.ml2 .letter',
    scale: [4,1],
    opacity: [0,1],
    translateZ: 0,
    easing: "easeOutExpo",
    duration: 950,
    delay: (el, i) => 70*i
  }).add({
    targets: '.ml2',
    opacity: 0,
    duration: 1000,
    easing: "easeOutExpo",
    delay: 2500
  })


// Animasi paragraf

  // Wrap every letter in a span
var textWrapper = document.querySelector('.ml3');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: true})
  .add({
    targets: '.ml3 .letter',
    opacity: [0,1],
    easing: "easeOutExpo",
    duration: 950,
    delay: (el, i) => 70 * (i)
  }).add({
    targets: '.ml3',
    opacity: 0,
    duration: 1000,
    easing: "easeOutExpo",
    delay: 1000
  });