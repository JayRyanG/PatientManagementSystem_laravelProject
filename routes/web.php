<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [PatientController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

//Patient Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    Route::get('/patients/trash', [PatientController::class, 'trash'])->name('patients.trash');
    Route::post('/patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    Route::delete('/patients/{id}/force-delete', [PatientController::class, 'forceDelete'])->name('patients.force-delete');

    Route::get('/patients/export', [PatientController::class, 'export'])->name('patients.export');
});

//Doctor Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
