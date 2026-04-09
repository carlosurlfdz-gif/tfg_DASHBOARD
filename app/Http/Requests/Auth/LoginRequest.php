<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        // comprobamos limite de intentos (5)
        $this->ensureIsNotRateLimited();


        if (!Auth::attempt($this->only('username', 'password'))) {
            // si falla la autenticación, incrementamos el contador de intentos
            RateLimiter::hit($this->throttleKey());

            // y lanzamos una excepción de validación con un mensaje de error
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }
        // limpiamos el contador de intentos en caso de exito
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // Si el número de intentos no supera el límite, simplemente retornamos
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Disparamos un evento de bloqueo para que podamos registrar el intento de acceso fallido
        event(new Lockout($this));

        // obtenemos el tiempo restante para poder volver a intentar
        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Si superamos el límite de intentos, lanzamos un error indicando el tiempo que falta para poder volver a intentar
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')) . '|' . $this->ip());
    }
}
