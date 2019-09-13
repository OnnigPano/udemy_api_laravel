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

//Buyers

Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);

//Categories
Route::apiResource('categories', 'Category\CategoryController');

//Products
Route::resource('products', 'Category\CategoryController', ['only' => ['index', 'show']]);

//Transactions
Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show']]);

//Sellers
Route::resource('sellers', 'Seller\SellerController', ['only' => ['index', 'show']]);

//Users
Route::apiResource('users', 'User\UserController');
