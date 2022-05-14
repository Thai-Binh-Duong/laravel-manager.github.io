<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function(){
    Route::get('dashboard','DashboardController@show');

    Route::get('admin','DashboardController@show');

    Route::get('admin/user/list','AdminUserController@list');

    Route::get('admin/user/add','AdminUserController@add');

    Route::post('admin/user/store','AdminUserController@store');

    Route::get('admin/user/delete/{id}','AdminUserController@delete')->name('user_delete');

    Route::get('admin/user/edit/{id}','AdminUserController@edit')->name('user.edit');

    Route::post('admin/user/update/{id}','AdminUserController@update')->name('user.update');
});

Route::middleware('auth')->group(function(){
    
    Route::get('admin/product/list','ProductController@list');

    Route::get('admin/product/add','ProductController@add');

    Route::get('admin/product/cat/list','ProductController@cate');
});



