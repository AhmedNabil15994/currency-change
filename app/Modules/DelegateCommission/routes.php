<?php

/*----------------------------------------------------------
Commissions
----------------------------------------------------------*/
Route::group(['prefix' => '/commissions'] , function () {
    Route::get('/', 'CommissionsControllers@index');
    Route::get('edit/{id}', 'CommissionsControllers@edit');
    Route::post('update/{id}', 'CommissionsControllers@update');
    Route::get('add', 'CommissionsControllers@add');
    Route::post('create', 'CommissionsControllers@create');
    Route::get('delete/{id}', 'CommissionsControllers@delete');
});