<?php
 
use think\facade\Route;


Route::group('/user', function() {
    Route::post('/login', 'User/login');
    Route::post('/register', 'User/register');
    Route::post('/addCollect', 'User/addcollect');
    Route::post('/addShoppingCart', 'User/addShoppingCart');
    Route::post('/getCollectList', 'User/getCollectList');
    Route::post('/getShoppingCartList', 'User/getShoppingCartList');
    Route::post('/deleteCollect', 'User/deleteCollect');
    Route::post('/deleteFromShoppingCart', 'User/deleteFromShoppingCart');
    Route::post('/deleteShoppingCart', 'User/deleteShoppingCart');
    Route::post('/updateShoppingCart', 'User/updateShoppingCart');
    Route::post('/getOrderList', 'User/getOrderList');
});

Route::group('/media', function() {
    Route::get('/', 'Media');
    Route::get('/likelist', 'Media/getLikeList');
    Route::get('/favorlist', 'Media/getFavorList');
});

Route::group('/product', function () {
    Route::get('/carousel', 'Product/getCarousel');
    Route::post('/category', 'Product/getCategory');
    Route::post('/categoryinfo', 'Product/getCategoryInfo');
    Route::post('/getAllProduct', 'Product/getAllProduct');
    Route::post('/getProductByID', 'Product/getProductByID');
    Route::post('/getDetails', 'Product/getDetails');
    Route::post('/getDetailsPicture', 'Product/getDetialsPicture');
    Route::post('/getProductBySearch', 'Product/getProductBySearch');
    Route::post('/getCommentByID', 'Product/getCommentByID');
});

Route::group('/admin', function () {
    Route::post('/getProductData', 'Admin/getProductData');
    Route::post('/getProductOrder', 'Admin/getProductOrder');
});