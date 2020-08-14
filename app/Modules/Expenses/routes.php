<?php

/*----------------------------------------------------------
Expenses
----------------------------------------------------------*/
Route::group(['prefix' => '/expenses'] , function () {
    Route::get('/', 'ExpensesControllers@index');
    Route::get('edit/{id}', 'ExpensesControllers@edit');
    Route::post('update/{id}', 'ExpensesControllers@update');
    Route::get('add', 'ExpensesControllers@add');
    Route::post('create', 'ExpensesControllers@create');
    Route::get('delete/{id}', 'ExpensesControllers@delete');
});

/*----------------------------------------------------------
Salaries
----------------------------------------------------------*/
Route::group(['prefix' => '/salaries'] , function () {
    Route::get('/', 'ExpensesControllers@salary_index');
    Route::get('update/{id}', 'ExpensesControllers@salary_update');
});

