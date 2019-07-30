<?php

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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/index.html', 'HomeController@index')->name('home');
//Route::get('login','HomeController@login')->name('login');
Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::get('/index', 'AdminController@index')->name('index');
    
});

//--Auth
Route::group(['namespace' => 'Auth', 'prefix' => 'member'], function ($router) {
    $router->get('/login.html', 'LoginController@showLoginForm')->name('login');
    $router->post('/login.html', 'LoginController@login')->name('postLogin');
    $router->get('/register.html', 'LoginController@showLoginForm')->name('register');
    $router->post('/register.html', 'RegisterController@register')->name('postRegister');
    $router->post('/logout', 'LoginController@logout')->name('logout');
    $router->post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $router->post('/password/reset', 'ResetPasswordController@reset');
    $router->get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    $router->get('/forgot.html', 'ForgotPasswordController@showLinkRequestForm')->name('forgot');
});
//End Auth
//Customer profile
Route::group(['prefix' => 'member', 'middleware' => 'auth'], function ($router) {
    $router->get('/', 'ShopAccount@index')->name('member.index');
    $router->get('/order_list.html', 'ShopAccount@orderList')->name('member.order_list');
    $router->get('/change_password.html', 'ShopAccount@changePassword')->name('member.change_password');
    $router->post('/change_password.html', 'ShopAccount@postChangePassword')->name('member.post_change_password');
    $router->get('/change_infomation.html', 'ShopAccount@changeInfomation')->name('member.change_infomation');
    $router->post('/change_infomation.html', 'ShopAccount@postChangeInfomation')->name('member.post_change_infomation');
    $router->get('/wishlist.html','ShopAccount@wishList')->name('member.wishlist');
    $router->get('/addtowishlist/{id}','ShopAccount@addToWishList')->name('member.addtowishlist');
    $router->post('/checkout.html','ShopAccount@checkout')->name('member.checkout');
    $router->get('/checkoutconfirm.html','ShopAccount@checkoutConfirm')->name('member.checkoutconfirm');
});
//End customer

Route::prefix('product')->namespace('Product')->name('product.')->group(function () {
    //Route::get('/details', 'ProductController@details')->name('details');
    Route::get('/cart', 'ProductController@cart')->name('cart');
    Route::get('/details/{id}','ProductController@details')->name('details');
    Route::get('/{id}','ProductController@addToCart')->name('addtocart');
    Route::get('/cart/{id}/{qty}','ProductController@updateCart')->name('updatecart');
    Route::get('/update/{id}','ProductController@updateToCart')->name('updatetocart');
    Route::get('/clear','ProductController@clearCart')->name('clearcart');
});
//Language
Route::get('locale/{code}', function ($code) {
    session(['locale' => $code]);
    app()->setLocale($code);
    return back();
})->name('locale');