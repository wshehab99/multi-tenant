<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-tenants', function () {
    return \App\Models\Tenant::with('domains')->get();
});
