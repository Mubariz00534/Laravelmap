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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/googlemap', function () {
    return view('googleMaps');
});

Route::get('/othermap', function () {
    return view('otherMap');
});

Route::get('/leafletmap', function () {
    return view('leafletmap');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
