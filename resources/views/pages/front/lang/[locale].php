<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

// Allowed locales
$allowed = ['ru', 'en', 'eo'];
$locale ??= 'ru';

if (in_array($locale, $allowed)) {
    Session::put('locale', $locale);
    app()->setLocale($locale);
}

// Redirect back to referring page or home
return Redirect::to(request()->header('referer') ?? route('home'));
