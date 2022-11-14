<?php

use Illuminate\Support\Facades\Route;

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

//routes
Route::get('/', 'BooksController@create')->name('book.create');
Route::post('/store', 'BooksController@store')->name('book.store');
Route::get('/edit/{id}', 'BooksController@edit')->name('book.edit');
Route::post('/update/{id}', 'BooksController@update')->name('book.update');
Route::post('/delete/{id}', 'BooksController@destroy')->name('book.destroy');
