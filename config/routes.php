<?php
use Dream\Route\Route;


Route::get('/')->to('home#index')->name('root');
Route::get("/login")->to('users#login')->name('users_login');
Route::get("/signup")->to('users#new')->name('users_sign_up');
Route::get("/users/:id/update")->to('users#update');
Route::get('/dashboard')->to('users#show')->name('users_profile');
Route::get('/users/logout')->to('users#logout')->name('users_session_destroy');
Route::post('/users/create')->to('users#create_user')->name('users_create');
Route::post("/users/signin/now")->to('users#verify')->name('users_verify');
Route::post("/users/:id/update")->to('users#update')->name('users_update');
Route::get('/users/:id/delete')->to('users#destroy')->name('users_destroy');

Route::get('/listings')->to('listings#all')->name('listings');
Route::get('/listings/create')->to('listings#new')->name('listings_create');
Route::get('/listings/:id/edit')->to('listings#edit')->name('listings_edit');
Route::get('/listings/:id/show')->to('listings#show')->name('listings_show');
Route::post('/listings/:id/update')->to('listings#update_listing')->name('listings_update');
Route::post('/listings/create/new')->to('listings#create')->name('listings_create_new');

Route::post('/messages/send')->to('messages#create');
Route::post('/contact')->to('home#contact');
