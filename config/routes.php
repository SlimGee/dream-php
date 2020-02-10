<?php
/*---------------------------------------------------------------------------
|   This is where you define your applicatio routes
|----------------------------------------------------------------------------
*/
use Dream\Route\Route;

Dream\Auth\Auth::routes();

Route::get('/', 'home#index')->name('root');
