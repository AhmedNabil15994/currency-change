<?php

/*----------------------------------------------------------
Transfers
----------------------------------------------------------*/
Route::group(['prefix' => '/transfers'] , function () {
    Route::get('/', 'TransfersControllers@index');
    Route::get('edit/{id}', 'TransfersControllers@edit');
    Route::post('update/{id}', 'TransfersControllers@update');
    Route::get('add', 'TransfersControllers@add');
    Route::post('create', 'TransfersControllers@create');
    Route::get('delete/{id}', 'TransfersControllers@delete');
    Route::get('getBanksAccounts/{id}', 'TransfersControllers@getBanksAccounts');
});