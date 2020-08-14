<?php

/*----------------------------------------------------------
Delegates
----------------------------------------------------------*/
Route::group(['prefix' => '/delegates'] , function () {
    Route::get('/', 'DelegatesControllers@index');
    Route::get('edit/{id}', 'DelegatesControllers@edit');
    Route::post('update/{id}', 'DelegatesControllers@update');
    Route::get('add', 'DelegatesControllers@add');
    Route::post('create', 'DelegatesControllers@create');
    Route::get('delete/{id}', 'DelegatesControllers@delete');
});