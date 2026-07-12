<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\InvitationController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

});

Route::livewire('/invite/{invitation}/redeem', 'pages::invitation.redeem')->name('invitation.redeem');

require __DIR__.'/settings.php';
