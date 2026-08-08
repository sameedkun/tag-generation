<?php

use App\Livewire\TagGenerator;
use Illuminate\Support\Facades\Route;

Route::livewire('/', TagGenerator::class)->name('tags.generator');
