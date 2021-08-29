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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/create-group', 'HomeController@createGroup');
Route::get('/group/{id}', 'HomeController@getGroup');
Route::post('/group/{id}', 'HomeController@createChat');
Route::get('insert', function () {
    $test = app('firebase.firestore')->database()->collection('Text')->newDocument();
    $test->set([
        'a' => 'viet',
        'b' => 'tran',
    ]);
});
