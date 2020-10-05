<?php

/*----------------------------------------------------------
Wallets
----------------------------------------------------------*/
Route::group(['prefix' => '/wallets'] , function () {
    Route::get('/', 'WalletControllers@index');
    Route::get('edit/{id}', 'WalletControllers@edit');
    Route::post('update/{id}', 'WalletControllers@update');
    Route::get('add', 'WalletControllers@add');
    Route::post('create', 'WalletControllers@create');
    Route::get('delete/{id}', 'WalletControllers@delete');
});