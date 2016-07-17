<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as'=>'home', function () {
    return view('welcome');
}]);

Route::get('/store', ['as'=>'store', 'uses'=>function() {
  return view('store');
}]);

Route::group(['prefix'=>'/admin'], function() {
    Route::get('/',                       ['as' => 'admin.auth',              'uses' => 'Admin\AuthController@index']);
    Route::get('login',                   ['as' => 'admin.auth.login',        'uses' => 'Admin\AuthController@login']);
    Route::post('doLogin',                ['as' => 'admin.auth.doLogin',      'uses' => 'Admin\AuthController@doLogin']);
    Route::get('logout',                  ['as' => 'admin.auth.logout',       'uses' => 'Admin\AuthController@logout']);
    Route::get('dashboard',               ['as' => 'admin.dashboard',         'uses' => 'Admin\DashboardController@index']);
    Route::get('profile',                 ['as' => 'admin.profile',           'uses' => 'Admin\DashboardController@profile']);
    Route::post('profile_store',          ['as' => 'admin.profilestore',      'uses' => 'Admin\DashboardController@profileStore']);
    Route::group(['prefix' => 'orders'], function () {
        Route::any('/',                         ['as' => 'admin.order',                     'uses' => 'Admin\OrderController@index']);
        Route::get('detail/{id}',               ['as' => 'admin.order.detail',              'uses' => 'Admin\OrderController@detail']);
        Route::get('delete/{id}',               ['as' => 'admin.order.delete',              'uses' => 'Admin\OrderController@delete']);
        Route::post('detail_update',            ['as' => 'admin.order.detailUpdate',        'uses' => 'Admin\OrderController@detailUpdate']);
        Route::post('shipping_detail_update',   ['as' => 'admin.order.shippingDetailUpdate','uses' => 'Admin\OrderController@shippingDetailUpdate']);
        Route::post('cancel_agree',             ['as' => 'admin.order.cancelAgree',         'uses' => 'Admin\OrderController@cancelAgree']);
    });
  Route::get('import',                  ['as'=>'admin.import',              'uses'=>'AdminImportController@import']);
  Route::post('import',                 ['as'=>'admin.do_import',           'uses'=>'AdminImportController@do_import']);
});


Route::get('/search', ['as'=>'search', 'uses'=>'SearchController@index']);

Route::get('/products/{slug}', ['as'=>'products.view', 'uses'=>'ProductController@view']);

Route::get('/categories/{slug}', ['as'=>'category.view', 'uses'=>function($slug) {
  return 'hi';
}]);

Route::group(['prefix'=>'cart'], function() {
  Route::get('add/{variation_id}', ['as'=>'cart.add', 'uses'=>'CartController@add']);
  Route::get('view', ['as'=>'cart.view', 'uses'=>'CartController@view']);
  Route::post('update', ['as'=>'cart.update', 'uses'=>'CartController@update']);
  Route::post('voucher', ['as'=>'cart.voucher', 'uses'=>'CartController@applyVoucher']);
  Route::post('shipping', ['as'=>'cart.shipping', 'uses'=>'CartController@applyShippingMethod']);
  Route::get('addOrder/{order_id}', ['as'=>'cart.addOrder', 'uses'=>'CartController@addOrder']);
});


Route::group(['prefix'=>'checkout'], function() {

    Route::get('/',                 ['as' => 'checkout',                'uses' => 'CheckoutController@checkout']);
    Route::post('proceed',          ['as'=>  'checkout.proceed',        'uses' => 'CheckoutController@proceed']);
    Route::post('address',          ['as' => 'checkout.address',        'uses' => 'CheckoutController@checkoutAddress']);
    Route::post('emailLogin',       ['as' => 'checkout.emailLogin',     'uses' => 'CheckoutController@emailLogin']);
    Route::post('do_checkout',      ['as' => 'checkout.do.new.address', 'uses' => 'CheckoutController@do_checkout']);
    Route::post('paypalNotify',     ['as' => 'checkout.paypalNotify',   'uses' => 'CheckoutController@paypalNotify']);
    Route::post('paypal',           ['as' => 'checkout.do.paypal',      'uses' => 'CheckoutController@doPaypal']);
    Route::get('thanks',            ['as' => 'checkout.thanks',         'uses' => 'CheckoutController@thanks']);
});

Route::get('orders',                ['as'=>'orders.index',                       'uses'=>'OrderController@index'] );
Route::get('orders/detail/{id}',    ['as'=>'orders.detail',                      'uses'=>'OrderController@detail']);
Route::post('cancel_order',         ['as'=>'orders.cancel',                      'uses'=>'OrderController@cancel']);

Route::post('checkout', ['as'=>'checkout.do', 'uses'=>'CheckoutController@do_checkout']);


Route::group(['middleware' => 'App\Http\Middleware\Secure'], function () {
    // SSO routes
    Route::get('auth/social/{driver}', 'Auth\AuthController@redirectToProvider')->name('sso_auth');
    Route::get('auth/social/{driver}/callback', 'Auth\AuthController@handleProviderCallback')->name('sso_auth_callback');

    // Authentication routes...
    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin')->name('login_post');
    Route::get('auth/logout', 'Auth\AuthController@getLogout')->name('logout');

    // Registration routes...
    Route::get('auth/register', 'Auth\AuthController@getRegister')->name('register');
    Route::post('auth/register', 'Auth\AuthController@postRegister')->name('register_post');
});

if ( ! function_exists('slugify') ) {
    function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return trim($text, '-');
    }
}