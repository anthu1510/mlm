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

Route::get('/', 'UserController@index');
Route::post('login', 'UserController@login');
Route::get('logout', 'UserController@logout');
Route::get('forgotpassword', 'UserController@forgotPassword');
Route::post('forgotcheck', 'UserController@forgotcheck');
Route::post('resetcodecheck', 'UserController@resetCodeCheck');
Route::get('forgotverify', 'UserController@forgotVerify');
Route::get('forgotverifycheck', 'UserController@forgotVerifycheck');
Route::post('newpassupdate', 'UserController@ResetPasswordUpdate');



// Authendicated Route
    Route::group(['middleware' => ['guest']], function () {
    Route::get('dashboard', 'DashboardController@index');

    Route::prefix('dashboard')->group(function () {
        //Tiles
        Route::post('gettiles', 'TilesController@GetTiles');
        Route::post('usersrecords', 'TilesController@UserWiseQuote');

        //Profile
        Route::get('profile', 'DashboardController@EditProfile');
        Route::post('profileupdate', 'DashboardController@Update');
        Route::get('passwordchange', 'DashboardController@passwordchange');
        Route::post('passwordupdate', 'DashboardController@updatepassword');

        //Users
        Route::get('users', 'UserController@Users');
        Route::post('users/store', 'UserController@Store');
        Route::post('users/useredit', 'UserController@UserEdit');
        Route::post('users/update', 'UserController@Update');
        Route::post('users/delete', 'UserController@UserDelete');
        Route::get('userserverside', 'UserController@UsersServerSide');



        //coupon
        Route::get('coupon', 'CouponController@index');
        Route::get('coupon/list', 'CouponController@list');
        Route::get('coupon/listactive', 'CouponController@listActive');
        Route::post('coupon/save', 'CouponController@save');
        Route::get('couponserverside', 'CouponController@CouponServerSide');
        Route::get('couponactiveserverside', 'CouponController@CouponActiveServerSide');
        Route::post('coupon/deactivate', 'CouponController@CouponDeactivate');
        Route::post('coupon/activate', 'CouponController@CouponActivate');
        Route::post('coupon/delete', 'CouponController@CouponDelete');
        Route::post('coupon/couponedit', 'CouponController@CouponEdit');
        Route::post('coupon/updatesave', 'CouponController@UpdateSave');


        //Node
        Route::get('node/aadharcheck', 'NodeController@AadharCheack');
        Route::get('node/pancheck', 'NodeController@PanCheack');
        Route::get('node/sponsercheck', 'NodeController@SponserCheck');
        Route::get('node/couponcheck', 'NodeController@CouponCheck');
        Route::get('node/getsponser', 'NodeController@GetSponser');
        Route::get('node/create', 'NodeController@Create');
        Route::post('node/save', 'NodeController@Save');
        Route::post('node/save2', 'NodeController@save2');
        Route::get('node/list', 'NodeController@list');
        Route::get('nodeserverside', 'NodeController@NodeServerSide');
        Route::post('node/nodeedit', 'NodeController@NodeEdit');
        Route::post('node/updatesave', 'NodeController@UpdateSave');
        Route::post('node/deactivate', 'NodeController@NodeDeactivate');
        Route::post('node/activate', 'NodeController@NodeActivate');
        Route::get('node/test/{id}', 'NodeController@getParent1');


        Route::get('comission/comissionlist', 'NodeController@comissionlist');
        Route::get('comission/comission/{id}', 'NodeController@comission');
        Route::get('comissionserverside/{id}', 'NodeController@ComissionServerSide');
        Route::get('comission/couponcomissionlist', 'NodeController@couponcomissionlist');
        Route::get('couponcomissionserverside', 'NodeController@CouponComissionServerSide');
        //Tree
        Route::get('tree/tree/{id}', 'TreeController@index');
        Route::get('tree/autotree/{id}', 'TreeController@AutoTree');
        Route::get('tree/goldtree/{id}', 'TreeController@GoldTree');
        Route::get('tree/silvertree/{id}', 'TreeController@SilverTree');
        Route::get('tree/list', 'NodeController@treelist');
        Route::get('nodetreeserverside', 'NodeController@NodeTreeServerSide');
        Route::get('settreevalues', 'NodeController@setTreeValues');  // for master setup..
        Route::get('testinsert', 'NodeController@testInsert');  // for master setup..





    });
});


