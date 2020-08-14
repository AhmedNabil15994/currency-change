<?php

/*----------------------------------------------------------
Shops
----------------------------------------------------------*/
Route::group(['prefix' => '/shops'] , function () {
    Route::get('/', 'ShopsControllers@index');
    Route::get('/edit/{id}', 'ShopsControllers@edit');
    Route::get('/view/{id}', 'ShopsControllers@view');
    Route::post('update/{id}', 'ShopsControllers@update');
    Route::get('add', 'ShopsControllers@add');
    Route::post('create', 'ShopsControllers@create');
    Route::get('delete/{id}', 'ShopsControllers@delete');
    Route::get('/images/delete/{id}', 'ShopsControllers@imageDelete');
});
