<?php


return [

    'DashboardControllers@index' => 'general',
    'DashboardControllers@getChartData' => 'general',
    'AuthControllers@login' => 'login',
    'AuthControllers@doLogin' => 'doLogin',
    'AuthControllers@logout' => 'logout',

    'UsersControllers@index' => 'list-users',
    'UsersControllers@edit' => 'edit-user',
    'UsersControllers@update' => 'edit-user',
    'UsersControllers@add' => 'add-user',
    'UsersControllers@create' => 'add-user',
    'UsersControllers@delete' => 'delete-user',

    'GroupsControllers@index' => 'list-groups',
    'GroupsControllers@edit' => 'edit-group',
    'GroupsControllers@update' => 'edit-group',
    'GroupsControllers@add' => 'add-group',
    'GroupsControllers@create' => 'add-group',
    'GroupsControllers@delete' => 'delete-group',

    'VariablesControllers@index' => 'list-variables',
    'VariablesControllers@edit' => 'edit-variable',
    'VariablesControllers@update' => 'edit-variable',
    'VariablesControllers@add' => 'add-variable',
    'VariablesControllers@create' => 'add-variable',
    'VariablesControllers@delete' => 'delete-variable',

    'ShopsControllers@index' => 'list-shops',
    'ShopsControllers@edit' => 'edit-shop',
    'ShopsControllers@update' => 'edit-shop',
    'ShopsControllers@add' => 'add-shop',
    'ShopsControllers@create' => 'add-shop',
    'ShopsControllers@delete' => 'delete-shop',
    'ShopsControllers@addImage' => 'add-shop-image',
    'ShopsControllers@imageDelete' => 'delete-shop-image',

    'DelegatesControllers@index' => 'list-delegates',
    'DelegatesControllers@edit' => 'edit-delegate',
    'DelegatesControllers@update' => 'edit-delegate',
    'DelegatesControllers@add' => 'add-delegate',
    'DelegatesControllers@create' => 'add-delegate',
    'DelegatesControllers@delete' => 'delete-delegate',

    'CommissionsControllers@index' => 'list-commissions',
    'CommissionsControllers@edit' => 'edit-commission',
    'CommissionsControllers@update' => 'edit-commission',
    'CommissionsControllers@add' => 'add-commission',
    'CommissionsControllers@create' => 'add-commission',
    'CommissionsControllers@delete' => 'delete-commission',

    'ClientsControllers@index' => 'list-clients',
    'ClientsControllers@edit' => 'edit-client',
    'ClientsControllers@update' => 'edit-client',
    'ClientsControllers@add' => 'add-client',
    'ClientsControllers@create' => 'add-client',
    'ClientsControllers@delete' => 'delete-client',

    'CurrenciesControllers@index' => 'list-currencies',
    'CurrenciesControllers@edit' => 'edit-currency',
    'CurrenciesControllers@update' => 'edit-currency',
    'CurrenciesControllers@add' => 'add-currency',
    'CurrenciesControllers@create' => 'add-currency',
    'CurrenciesControllers@delete' => 'delete-currency',

    'DetailsControllers@index' => 'list-details',
    'DetailsControllers@edit' => 'edit-details',
    'DetailsControllers@update' => 'edit-details',
    'DetailsControllers@add' => 'add-details',
    'DetailsControllers@create' => 'add-details',
    'DetailsControllers@delete' => 'delete-details',

    'ExchangeControllers@index' => 'list-exchanges',
    'ExchangeControllers@edit' => 'edit-exchange',
    'ExchangeControllers@update' => 'edit-exchange',
    'ExchangeControllers@add' => 'add-exchange',
    'ExchangeControllers@create' => 'add-exchange',
    'ExchangeControllers@delete' => 'delete-exchange',

    'WalletControllers@index' => 'list-wallets',
    'WalletControllers@edit' => 'edit-wallet',
    'WalletControllers@update' => 'edit-wallet',
    'WalletControllers@add' => 'add-wallet',
    'WalletControllers@create' => 'add-wallet',
    'WalletControllers@delete' => 'delete-wallet',

    'ExpensesControllers@index' => 'list-expenses',
    'ExpensesControllers@edit' => 'edit-expense',
    'ExpensesControllers@update' => 'edit-expense',
    'ExpensesControllers@add' => 'add-expense',
    'ExpensesControllers@create' => 'add-expense',
    'ExpensesControllers@delete' => 'delete-expense',

    'ExpensesControllers@salary_index' => 'list-salaries',
    'ExpensesControllers@salary_update' => 'edit-salary',

    'BankAccountsControllers@index' => 'list-bank-accounts',
    'BankAccountsControllers@edit' => 'edit-bank-account',
    'BankAccountsControllers@update' => 'edit-bank-account',
    'BankAccountsControllers@add' => 'add-bank-account',
    'BankAccountsControllers@create' => 'add-bank-account',
    'BankAccountsControllers@delete' => 'delete-bank-account',

    'StoragesControllers@index' => 'list-storages',
    'StoragesControllers@edit' => 'edit-storage',
    'StoragesControllers@update' => 'edit-storage',
    'StoragesControllers@add' => 'add-storage',
    'StoragesControllers@create' => 'add-storage',
    'StoragesControllers@delete' => 'delete-storage',

    'StorageTransfersControllers@index' => 'list-storage-transfers',
    'StorageTransfersControllers@edit' => 'edit-storage-transfer',
    'StorageTransfersControllers@update' => 'edit-storage-transfer',
    'StorageTransfersControllers@add' => 'add-storage-transfer',
    'StorageTransfersControllers@create' => 'add-storage-transfer',
    'StorageTransfersControllers@delete' => 'delete-storage-transfer',
    'StorageTransfersControllers@get_to' => 'list-storage-transfers',

    'TransfersControllers@index' => 'list-transfers',
    'TransfersControllers@getBanksAccounts' => 'list-transfers',
    'TransfersControllers@edit' => 'edit-transfer',
    'TransfersControllers@update' => 'edit-transfer',
    'TransfersControllers@add' => 'add-transfer',
    'TransfersControllers@create' => 'add-transfer',
    'TransfersControllers@delete' => 'delete-transfer',

    'ReportsControllers@expenses' => 'list-expenses-reports',
    'ReportsControllers@storages' => 'list-storages-reports',
    'ReportsControllers@bankAccounts' => 'list-bank-accounts-reports',
    'ReportsControllers@delegates' => 'list-delegates-reports',
    'ReportsControllers@clients' => 'list-clients-reports',
    'ReportsControllers@daily' => 'list-daily-reports',
    'ReportsControllers@yearly' => 'list-yearly-reports',

];
