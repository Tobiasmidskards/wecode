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

Route::get('/', 'PagesController@index');

Route::get('/feed', 'PagesController@feed');

Route::get('/dashboard', 'DashboardController@index');

Auth::routes();

// Auth middleware fungere ikke fra /api.php
Route::resource('/api/idea', 'IdeaController');
Route::get('/api/dashboard', 'IdeaController@dashboard');
