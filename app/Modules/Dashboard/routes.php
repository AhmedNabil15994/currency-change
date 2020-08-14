<?php

/*----------------------------------------------------------
Dashboard
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/', 'DashboardControllers@index');
    Route::post('/getChartData', 'DashboardControllers@getChartData');
});