<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\TagGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::livewire('/', TagGenerator::class)->name('tags.generator');

Route::get('/locale/{locale}', function (Request $request, string $locale) {
    abort_unless(array_key_exists($locale, SetLocale::SUPPORTED), 404);

    $request->session()->put('locale', $locale);

    return redirect()->back(fallback: route('tags.generator'));
})->name('locale.switch');
