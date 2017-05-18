<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('/templates', function () {
    return view('templates');
});

Route::get('exam/random-item/{type?}', 'ExamController@getRandomItem');
Route::get('exam/save-practice/{questionId}', 'ExamController@savePractice');