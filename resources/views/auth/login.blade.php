<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIEM SCALE PROJECT - Login</title>
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/login.css', 'resources/js/login.js'])
</head>

<body>
  <main class="page">
    <section class="login-card" aria-label="Formulario de inicio de sesión">
      <div class="brand">
        <div class="brand__icon" aria-hidden="true">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none">
            <path d="M12 2.5l7 3.6v6.2c0 5-3.2 9-7 10.2C8.2 21.3 5 17.3 5 12.3V6.1L12 2.5z" stroke="currentColor"
              stroke-width="1.8" stroke-linejoin="round" />
          </svg>
        </div>

        <h1 class="brand__title">SIEM SCALE PROJECT</h1>
        <p class="brand__subtitle">Plataforma de Monitorización de Seguridad para PYMEs</p>
      </div>

      <form method="POST" action="{{ route('login') }}" class="form" autocomplete="on">
        
        @csrf <!-- Protección CSRF (ataques Cross-Site Request Forgery) -->

        <div id="loginError" class="login-error"
          style="display: {{ $errors->any() ? 'block' : 'none' }}; color: #ff4d4f; background: #fff1f0; border: 1px solid #ffa39e; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 16px; text-align: center;">
          @if($errors->any())
            {{ $errors->first() }}
          @endif
        </div>

        <label class="field">
            <span class="field__label">Usuario</span>
            <div class="input">
                <span class="input__icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M12 13a5 5 0 1 0-5-5 5 5 0 0 0 5 5z" stroke="currentColor" stroke-width="1.8"
                    stroke-linejoin="round" />
                </svg>
                </span>
                <input id="username" type="text" name="username" placeholder="Ingresa tu usuario" value="{{ old('username') }}"
                required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </label>

        <label class="field">
          <span class="field__label">Contraseña</span>
          <div class="input input--password">
            <span class="input__icon" aria-hidden="true">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M7.5 14.5a4.5 4.5 0 1 1 3.9-6.8l9.1 0v3h-2v2h-2v2h-2.1" stroke="currentColor"
                  stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.5 12.5h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
              </svg>
            </span>

            <input id="password" type="password" name="password" placeholder="Ingresa tu contraseña" required
              autocomplete="current-password" />

            <button class="input__action" type="button" id="togglePassword" aria-label="Mostrar contraseña">
              <svg class="eye eye--open" width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="1.8"
                  stroke-linejoin="round" />
                <path d="M12 15a3 3 0 1 0-3-3 3 3 0 0 0 3 3z" stroke="currentColor" stroke-width="1.8"
                  stroke-linejoin="round" />
              </svg>
              <svg class="eye eye--closed" width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M3 3l18 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                <path d="M2 12s3.5-7 10-7c2.2 0 4.1.7 5.7 1.7" stroke="currentColor" stroke-width="1.8"
                  stroke-linecap="round" />
                <path d="M22 12s-3.5 7-10 7c-2.4 0-4.5-.8-6.2-2" stroke="currentColor" stroke-width="1.8"
                  stroke-linecap="round" />
                <path d="M10.2 10.2A3 3 0 0 0 12 15a3 3 0 0 0 1.8-.6" stroke="currentColor" stroke-width="1.8"
                  stroke-linecap="round" />
              </svg>
            </button>
          </div>
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </label>

        <div class="btn-row">
          <button class="btn btn--primary" type="submit">INICIAR SESIÓN</button>
        </div>

        <!-- @if (Route::has('password.request'))
          <a class="link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        @endif -->

        <p class="footer">© 2026 SIEM Scale Project - Seguridad para PYMEs</p>
      </form>
    </section>
  </main>
</body>

</html>