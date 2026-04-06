<?php

use Illuminate\Support\Facades\Route;

// Language switcher route
Route::get('/lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'ru', 'eo'])) {
        abort(400, 'Unsupported locale');
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch')->middleware('web');

// Most of the routes moved to the Folio file-based system

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
