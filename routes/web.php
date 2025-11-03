<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\InvoiceController;

// الصفحة الرئيسية
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Client management
    Route::get('/clients', [ClientController::class, 'adminIndex'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'adminCreate'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'adminStore'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'adminShow'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'adminEdit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'adminUpdate'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'adminDestroy'])->name('clients.destroy');
    
    Route::resource('projects', ProjectController::class);
    Route::resource('time-entries', TimeEntryController::class);
    Route::patch('/time-entries/{timeEntry}/approve', [TimeEntryController::class, 'approve'])->name('time-entries.approve');
    Route::patch('/time-entries/{timeEntry}/reject', [TimeEntryController::class, 'reject'])->name('time-entries.reject');
    Route::resource('invoices', InvoiceController::class);
    Route::patch('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::patch('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    Route::patch('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::post('/invoices/generate-from-entries', [InvoiceController::class, 'generateFromTimeEntries'])->name('invoices.generate-from-entries');
});

// Client Routes
Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects', [ClientController::class, 'projects'])->name('projects');
    Route::get('/time-entries', [ClientController::class, 'timeEntries'])->name('time-entries');
    Route::get('/reports', [ClientController::class, 'reports'])->name('reports');
    Route::get('/invoices', [ClientController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoice}', [ClientController::class, 'showInvoice'])->name('invoices.show');
    Route::patch('/time-entries/{timeEntry}/approve', [ClientController::class, 'approveTimeEntry'])->name('time-entries.approve');
    Route::patch('/time-entries/{timeEntry}/reject', [ClientController::class, 'rejectTimeEntry'])->name('time-entries.reject');
});
