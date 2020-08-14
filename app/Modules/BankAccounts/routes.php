<?php

/*----------------------------------------------------------
Bank Accounts
----------------------------------------------------------*/
Route::group(['prefix' => '/bank-accounts'] , function () {
    Route::get('/', 'BankAccountsControllers@index');
    Route::get('edit/{id}', 'BankAccountsControllers@edit');
    Route::post('update/{id}', 'BankAccountsControllers@update');
    Route::get('add', 'BankAccountsControllers@add');
    Route::post('create', 'BankAccountsControllers@create');
    Route::get('delete/{id}', 'BankAccountsControllers@delete');
});