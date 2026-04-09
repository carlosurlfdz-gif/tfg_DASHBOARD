document.addEventListener('DOMContentLoaded', function() {
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');

  if (!togglePassword || !passwordInput) return;

  togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    const eyeOpen = this.querySelector('.eye--open');
    const eyeClosed = this.querySelector('.eye--closed');

    if (type === 'text') {
      eyeOpen.style.display = 'none';
      eyeClosed.style.display = 'inline';
      this.setAttribute('aria-label', 'Ocultar contraseña');
    } else {
      eyeOpen.style.display = 'inline';
      eyeClosed.style.display = 'none';
      this.setAttribute('aria-label', 'Mostrar contraseña');
    }
  });
});