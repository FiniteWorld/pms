<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});

Route::apiResources([
    'user' => 'API\UserController',
    'project'=> 'API\ProjectController',
    'event'=>'EventsController',
    'todo' => 'TodoController',
    'team'=>'API\TeamController',
    'm_projects'=>'API\MemberController',
    'clients' => 'API\ClientsController',
    'tasks'=>'TaskController'
    
    ]);

Route::get('status','TaskController@loadStatus');
Route::get('statusManager','TaskController@loadProjectStatus');
Route::get('counts','API\UserController@totalCount');
Route::get('client_info','API\ProjectController@loadClients');
Route::get('findClient','API\ClientsController@search');

Route::get('message','ChatController@fetchMessages');
Route::post('message','ChatController@send');

Route::get('member','API\TeamController@loadMembers');
Route::get('titles','API\TeamController@loadTitles');

Route::get('profile','API\UserController@profile');

Route::put('findUser','API\UserController@search');

Route::put('profile','API\UserController@updateProfile');


