<?php
/*---------------------------------------------------------------------------
|   This is where you define your applicatio routes
|----------------------------------------------------------------------------
*/
use Dream\Route\Route;


Route::get('/')->to('home#index')->name('root');
