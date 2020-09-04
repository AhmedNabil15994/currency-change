<?php

/*----------------------------------------------------------
Storage Transfers
----------------------------------------------------------*/
Route::group(['prefix' => '/storage-transfers'] , function () {
    Route::get('/', 'StorageTransfersControllers@index');
    Route::get('edit/{id}', 'StorageTransfersControllers@edit');
    Route::post('update/{id}', 'StorageTransfersControllers@update');
    Route::get('add', 'StorageTransfersControllers@add');
    Route::get('get_to/{id}', 'StorageTransfersControllers@get_to');
    Route::post('create', 'StorageTransfersControllers@create');
    Route::get('delete/{id}', 'StorageTransfersControllers@delete');
});