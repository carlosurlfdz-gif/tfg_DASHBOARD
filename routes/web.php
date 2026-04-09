<?php
// Definimos las rutas HTTP
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;


// Ruta principal de la aplicación
Route::get('/', function () {
    return view('auth.login');
});


// Ruta del dashboard
Route::get('/dashboard', function () {
    
    return view('dashboard');

    // Solo puede acceder un usuario autenticado
})->middleware(['auth', 'verified'])->name('dashboard');


// Grupo de rutas para usuarios no logueados 
Route::middleware('guest')->group(function () {

    // Muestra el formulario de registro
    // Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

    // Procesa el envío del formulario de registro
    // Route::post('register', [RegisteredUserController::class, 'store']);

    // Muestra el formulario de login
    // Devuelve la vista auth/login.blade.php
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

    // Procesa el inicio de sesión
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Muestra el formulario para solicitar recuperación de contraseña
    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

    // Envía el email con el enlace de recuperación
    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Muestra el formulario para restablecer la contraseña usando el token
    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

    // Guarda la nueva contraseña
    // Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});


// Grupo de rutas solo accesibles para usuarios autenticados
Route::middleware('auth')->group(function () {

    // Muestra la vista que pide al usuario verificar su email
    // Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    // Verifica el email desde el enlace recibido
    // 'signed' comprueba que la URL es válida y no ha sido manipulada
    // 'throttle:6,1' limita a 6 intentos por minuto
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    // Reenvía el correo de verificación
    // También limitado a 6 intentos por minuto
    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');

    // Muestra el formulario para confirmar la contraseña actual
    // Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

    // Procesa la confirmación de la contraseña
    // Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Actualiza la contraseña del usuario autenticado
    // Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Cierra la sesión del usuario
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


// Otro grupo de rutas protegido por autenticación
Route::middleware('auth')->group(function () {

    // Muestra el formulario de edición del perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Actualiza los datos del perfil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Elimina la cuenta del usuario
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});