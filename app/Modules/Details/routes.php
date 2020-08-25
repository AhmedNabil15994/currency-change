<?php

/*----------------------------------------------------------
Details
----------------------------------------------------------*/
Route::group(['prefix' => '/details'] , function () {
    Route::get('/', 'DetailsControllers@index');
    Route::get('edit/{id}', 'DetailsControllers@edit');
    Route::post('update/{id}', 'DetailsControllers@update');
    Route::get('add', 'DetailsControllers@add');
    Route::post('create', 'DetailsControllers@create');
    Route::get('delete/{id}', 'DetailsControllers@delete');
});