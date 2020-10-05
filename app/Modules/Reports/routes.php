<?php

/*----------------------------------------------------------
Reports
----------------------------------------------------------*/
Route::group(['prefix' => '/reports'] , function () {
    Route::get('/expenses', 'ReportsControllers@expenses');
    Route::get('/storages', 'ReportsControllers@storages');
    Route::get('/bankAccounts', 'ReportsControllers@bankAccounts');
    Route::get('/delegates', 'ReportsControllers@delegates');
    Route::get('/clients', 'ReportsControllers@clients');
    Route::get('/daily', 'ReportsControllers@daily');
    Route::get('/yearly', 'ReportsControllers@yearly');
});
