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
use App\Events\ChatEvent;
use App\Events\TaskStatus;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/fire', function () {
    event(new TaskStatus);
    return "FIRE";
});
//Route::get('message', 'ChatController@index');


Auth::routes();

Route::get('/contacts', 'ContactsController@get');
Route::get('/conversation/{id}', 'ContactsController@getMessagesFor');
Route::post('/conversation/send', 'ContactsController@send');
Route::get('/chat', 'HomeController@index')->name('home');

Route::get('{path}',  'HomeController@index')->where('path','[\/\w\.-]*' );



