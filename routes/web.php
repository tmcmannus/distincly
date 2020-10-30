<?php

use Illuminate\Support\Facades\Route;

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

Route::match(['get','post'],'/admin','AdminController@login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>['auth']], function(){
    Route::get('/admin/dashboard','AdminController@dashboard')->name('admin.dashboard');
    Route::get('/admin/settings','AdminController@settings')->name('admin.settings');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get','post'],'/admin/update-pwd','AdminController@updatePassword')->name('admin.update-pwd');
    
    //Categories Route (Admin)
    Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory')->name('addCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory')->name('editCategory');
    Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory')->name('deleteCategory');
    Route::get('admin/view-categories','CategoryController@viewCategories')->name('viewCategories');

    //Products Route (Admin)
    Route::match(['get','post'],'/admin/add-product','ProductController@addProduct')->name('addProducts');
    Route::match(['get','post'],'/admin/edit-product/{id}','ProductController@editProduct')->name('editProduct');
    Route::get('/admin/view-products','ProductController@viewProducts')->name('viewProducts');
    Route::get('/admin/delete-product/{id}','ProductController@deleteProduct')->name('deleteProduct');
    Route::get('/admin/delete-product-image/{id}','ProductController@deleteProductImage')->name('deleteProductImage');

    Route::match(['get','post'],'admin/add-attributes/{id}','ProductController@addAttributes')->name('addAttributes');
    
    
});


Route::get('/logout','AdminController@logout')->name('admin.logout');





