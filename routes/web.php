<?php

Route::get('/', function (){
    return view('main');
});
//First version of this task
Route::prefix('v1')->group(function () {
    Route::get('/create', 'v1\MainController@getInsertContacts');
    Route::post('/create', 'v1\MainController@postInsertContacts');

    Route::get('/get-contacts', 'v1\MainController@getZohoContacts');
    Route::get('/contacts', 'v1\MainController@getContacts');

});
//First second of this task
Route::prefix('v2')->group(function () {

    Route::get('/create', 'v2\MainController@getInsertContacts');
    Route::post('/create', 'v2\MainController@postInsertContacts');

    Route::get('/get-contacts', 'v2\MainController@getZohoContacts');
    Route::get('/contacts', 'v2\MainController@getContacts');
});
