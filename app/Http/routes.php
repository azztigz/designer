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

Route::get('request', 'RequestController@index');
Route::any('access_token', 'TokenController@index');

Route::any('describe/{work_id}', 'TokenController@describe');
Route::any('getdata', 'TokenController@getdata');



Route::group(['prefix'=>'editor', 'middleware' => 'UserAuth'], function(){

    Route::get('/', 'MainPageController@index');
    Route::get('works', 'MainPageController@works');
    Route::get('categories', 'MainPageController@categories');
    Route::get('tempPage', 'MainPageController@tempPage');
    Route::get('image/{type}/{id}/{preview?}', 'MainPageController@image');
    Route::get('svgeditor', 'MainPageController@editor');
    Route::post('editorSaveFile', 'MainPageController@editorSaveFile');
    Route::post('copytemp', 'MainPageController@editorCopyTemp');
    Route::get('pdf', 'MainPageController@editorPdf');

    Route::get('library', 'library\LibraryPageController@index');
    Route::get('photos', 'library\LibraryPageController@photos');
    Route::get('photoimg/{id}', 'library\LibraryPageController@photoimg');
    Route::post('uploadFile', 'library\LibraryPageController@uploadFile');

    Route::get('watermark/{id}', 'library\LibraryPageController@watermark');

    Route::get('search', 'library\FotoliaController@index');
    Route::get('getmedia', 'library\FotoliaController@getMedia');
    Route::get('buymedia', 'library\FotoliaController@buyMedia');


});

Route::group(['prefix'=>'admin', 'middleware' => 'auth'], function(){
    
    Route::get('/', 'admin\AdminController@index');
    Route::get('login', 'admin\AdminController@login');
    Route::post('auth', 'admin\AdminController@auth');
    Route::get('logout', 'admin\AdminController@logout');
    Route::get('templates/{slug?}', 'admin\AdminController@templates');
    Route::get('templates/edit/{slug?}', 'admin\AdminController@templates');
    Route::get('categories', 'admin\AdminController@categories');
    Route::post('newTemp', 'admin\AdminController@newTemp');
    Route::post('updateTemp', 'admin\AdminController@updateTemp');
    Route::post('uploadFile/{type}', 'admin\AdminController@uploadFile');
    Route::post('delTemp', 'admin\AdminController@delTemp');
    Route::get('image/{type}/{id}/{preview?}', 'admin\AdminController@image');

});

Route::group(['prefix'=>'testing'], function(){
    
    Route::get('/', 'TempController@pickTemplate');
    Route::get('editor', ['uses'=>'TempController@index', 'as'=>'temp.editor']);
    Route::post('filesave', ['uses'=>'TempController@fileSave', 'as'=>'temp.filesave']);

    Route::get('search', 'FotoliaController@index');
    Route::get('get-media', 'FotoliaController@getMedia');
    Route::get('buy-media', 'FotoliaController@buyMedia');

});

Route::get('/', function(){
    // echo 'Your session has been expired!!!';
    echo 'Your are not authorized to access this site!!!';
});

//Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
