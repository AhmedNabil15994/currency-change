<?php

/*----------------------------------------------------------
Bank Accounts
----------------------------------------------------------*/
Route::group(['prefix' => '/storages'] , function () {
    Route::get('/', 'StoragesControllers@index');
    Route::get('edit/{id}', 'StoragesControllers@edit');
    Route::post('update/{id}', 'StoragesControllers@update');
    Route::get('add', 'StoragesControllers@add');
    Route::post('create', 'StoragesControllers@create');
    Route::get('delete/{id}', 'StoragesControllers@delete');
});