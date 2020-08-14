<?php

/*----------------------------------------------------------
Currencies
----------------------------------------------------------*/
Route::group(['prefix' => '/currencies'] , function () {
    Route::get('/', 'CurrencyControllers@index');
    Route::get('edit/{id}', 'CurrencyControllers@edit');
    Route::post('update/{id}', 'CurrencyControllers@update');
    Route::get('add', 'CurrencyControllers@add');
    Route::post('create', 'CurrencyControllers@create');
    Route::get('delete/{id}', 'CurrencyControllers@delete');
});