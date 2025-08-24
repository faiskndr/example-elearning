<?php

use App\Livewire\KuisForm;
use App\Livewire\KuisList;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/counter', Counter::class);
Route::get('/kuis', KuisList::class);
Route::get('/kuis/form', KuisForm::class);
Route::get('/kuis/{id}/edit', KuisForm::class);