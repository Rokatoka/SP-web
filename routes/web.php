<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/login', function () {
    return view('app');
})->name('login');

Route::get('/select-account', function () {
    return view('app');
});

Route::get('/reset-pass',function() {
    return view('app');
});

Route::get('/test', function () {
    return view('app');
});

Route::get('/', function () {
    return view('app');
});

Route::get('/dashboard', function () {
    return view('app');
});

Route::get('/users', function () {
    return view('app');
});
Route::get('/user/{id}', function () {
    return view('app');
});

Route::get('/control', function () {
    return view('app');
});

Route::get('/control/{path}', function () {
    return view('app');
});

Route::get('/control/{path}/{path2}', function () {
    return view('app');
});

Route::get('/register', function () {
    return view('app');
});

Route::get('/histories', function () {
    return view('app');
});

Route::get('/doctors', function () {
    return view('app');
});

Route::get('/hospitals', function () {
    return view('app');
});

Route::get('/appointments', function () {
    return view('app');
});

Route::get('/appointment', function () {
    return view('app');
});

Route::get('/appointments/{id}', function () {
    return view('app');
});

Route::get('/histories/{id}', function () {
    return view('app');
});

Route::get('/hospitals/{id}', function () {
    return view('app');
});

Route::get('/hospitals/{id}/departments', function () {
    return view('app');
});

Route::get('/departments/{id}', function () {
    return view('app');
});
