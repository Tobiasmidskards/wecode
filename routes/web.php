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

Route::get('/create', 'PagesController@create');

Route::get('/idea/{id}', 'PagesController@show');

Route::get('/edit/{id}', 'PagesController@edit');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Jeg kan ikke få auth til at fungere fra api.php
//Route::get('/api/idea', 'IdeaController@index');

//Route::post('/api/idea', 'IdeaController@store');

Route::resource('/api/idea', 'IdeaController');
