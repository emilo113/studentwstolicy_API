<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// User requests
Route::post('/user/sign-up', [
    'uses' => 'UserController@signUp'
]);

Route::post('/user/sign-in', [
    'uses' => 'UserController@signIn'
]);

Route::get('/user', [
    'uses' => 'UserController@isAuthenticated',
    'middleware' => 'auth.jwt'
]);

// Category requests
Route::post('/category', [
    'uses' => 'CategoryController@createCategory',
    'middleware' => 'auth.jwt'
]);

Route::get('/categories', [
    'uses' => 'CategoryController@getCategories',
]);

// City requests
Route::post('/city', [
    'uses' => 'CityController@createCity',
    'middleware' => 'auth.jwt'
]);

Route::get('/cities', [
    'uses' => 'CityController@getCities',
]);

// Place requests
Route::get('/places', [
    'uses' => 'PlaceController@getPlacesForCategoryAndCity'
]);

Route::get('/places/inactive', [
    'uses' => 'PlaceController@getInactivePlaces'
]);

Route::post('/place', [
    'uses' => 'PlaceController@createPlace',
    'middleware' => 'auth.jwt'
]);

Route::put('/place/{id}', [
    'uses' => 'PlaceController@moderatePlace',
    'middleware' => 'auth.jwt'
]);

// Favorite places requests

Route::get('/favorite-places', [
    'uses' => 'FavoritePlaceController@getFavoritePlacesForAccount',
    'middleware' => 'auth.jwt'
]);

Route::post('/favorite-place', [
    'uses' => 'FavoritePlaceController@toggleFavoritePlace',
    'middleware' => 'auth.jwt'
]);

Route::get('/favorite-place/{id}', [
   'uses' => 'FavoritePlaceController@isFavoritePlace',
   'middleware' => 'auth.jwt'
]);

