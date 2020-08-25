<?php

/*----------------------------------------------------------
Exchanges
----------------------------------------------------------*/
Route::group(['prefix' => '/exchanges'] , function () {
    Route::get('/', 'ExchangeControllers@index');
    Route::get('edit/{id}', 'ExchangeControllers@edit');
    Route::post('update/{id}', 'ExchangeControllers@update');
    Route::get('add', 'ExchangeControllers@add');
    Route::post('create', 'ExchangeControllers@create');
    Route::get('delete/{id}', 'ExchangeControllers@delete');
});