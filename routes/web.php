<?php

Route::get('/', 'LandingPageController@index')->name('landing-page');

Route::get('/loja', 'LojaController@index')->name('shop.index');
Route::get('/loja/{produto}', 'LojaController@show')->name('shop.show');

Route::get('/carrinho', 'CarrinhoController@index')->name('cart.index');
Route::post('/carrinho/{produto}', 'CarrinhoController@store')->name('cart.store');
Route::patch('/carrinho/{produto}', 'CarrinhoController@update')->name('cart.update');
Route::delete('/carrinho/{produto}', 'CarrinhoController@destroy')->name('cart.destroy');

Route::post('/cupom', 'CuponsController@store')->name('coupon.store');
Route::delete('/cupom', 'CuponsController@destroy')->name('coupon.destroy');

Route::get('/checkout', 'CheckoutController@index')->name('checkout.index')->middleware('auth');
Route::post('/checkout', 'CheckoutController@store')->name('checkout.store');
Route::get('/guestCheckout', 'CheckoutController@index')->name('guestCheckout.index');

Route::get('/obrigado', 'ConfirmationController@index')->name('confirmation.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/pesquisar', 'LojaController@search')->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', 'UsersController@edit')->name('users.edit');
    Route::patch('/perfil', 'UsersController@update')->name('users.update');

    Route::get('/meus-pedidos', 'PedidosController@index')->name('orders.index');
    Route::get('/meus-pedidos/{pedido}', 'PedidosController@show')->name('orders.show');
});
