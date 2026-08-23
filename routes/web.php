<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\InvitationController;

Route::livewire('/', 'pages::frontend.home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/pick', [TeamSelectionController::class, 'index'])->name('teams.pick');
});

Route::livewire('/dashboard', 'pages::frontend.dashboard')->name('dashboard');
Route::livewire('/invite/{invitation}/redeem', 'pages::invitation.redeem')->name('invitation.redeem');

require __DIR__.'/settings.php';
