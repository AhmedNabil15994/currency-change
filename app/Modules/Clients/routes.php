<?php

/*----------------------------------------------------------
Clients
----------------------------------------------------------*/
Route::group(['prefix' => '/clients'] , function () {
    Route::get('/', 'ClientsControllers@index');
    Route::get('edit/{id}', 'ClientsControllers@edit');
    Route::post('update/{id}', 'ClientsControllers@update');
    Route::get('add', 'ClientsControllers@add');
    Route::post('create', 'ClientsControllers@create');
    Route::get('delete/{id}', 'ClientsControllers@delete');
});