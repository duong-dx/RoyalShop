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

Route::get('/', function () {
 return view('shop.home');
});
Route::get('/test', function () {
 return view('test');
});
Route::prefix('admin')->group(function(){
     Auth::routes();

     Route::middleware('auth')->group(function(){
          Route::resource('images','ImageController');
               /**
                    * Tác dụng : lấy thông tin và sử lý categories
                    *
                    * @param  name space
                    * @param  int   biến chuyền vào
                    * @return \Illuminate\Http\Response trả về gì
                    */
               Route::get('/getCategories','CategoryController@getCategories')->name('getCategories');
               Route::resource('categories','CategoryController');

               /**
                    * Tác dụng : sủ lý option 
                    */
               Route::get('/getOptions','OptionController@getOptions')->name('getOptions');
               Route::resource('options','OptionController');

               /**
                    * Tác dụng : sủ lý  option_vlaues
                    */
               Route::get('/getOptionValues/{id}','OptionValueController@getOptionValues')->name('getOptionValues');
               Route::resource('option_vlaues','OptionValueController');

               /**
                    * Tác dụng : sủ lý  colors
                    */
               Route::get('/getColors','ColorController@getColors')->name('getColors');
               Route::resource('colors','ColorController');


               /**
                    * Tác dụng : sủ lý  brands
                    */
               Route::get('/getBrands','BrandController@getBrands')->name('getBrands');
               Route::resource('brands','BrandController');


               /**
                    * Tác dụng : sủ lý  users
                    */
               Route::get('/getUsers','UserController@getUsers')->name('getUsers');
               Route::resource('users','UserController');


               /**
                    * Tác dụng : sủ lý  customers
                    */
               Route::get('/getCustomers','CustomerController@getCustomers')->name('getCustomers');
               Route::resource('customers','CustomerController');


               /**
                    * Tác dụng : sủ lý  products
                    */
               Route::get('/getProducts','ProductController@getProducts')->name('getProducts');
               Route::resource('products','ProductController');
               Route::post('/addImages','ProductController@addImages');

               /**
                    * Tác dụng : sủ lý  branches
                    */
               Route::get('/getBranches','BranchController@getBranches')->name('getBranches');
               Route::resource('branches','BranchController');
               

               /**
                    * Tác dụng : sủ lý  detail_products
                    */
               Route::get('/getDetailProducts/{id}','DetailProductController@getDetailProducts')->name('getDetailProducts');
               Route::resource('detail_products','DetailProductController');

                /**
                    * Tác dụng : sủ lý  review
                    */
               Route::get('/getReviews/{id}','ReviewController@getReviews')->name('getReviews');
               Route::resource('reviews','ReviewController');
               
               /**
                    * Tác dụng : sủ lý  permissions
                    */
               Route::get('/getPermissions','PermissionController@getPermissions')->name('getPermissions');
               Route::resource('permissions','PermissionController');

     });
});


Route::get('/home', 'HomeController@index')->name('home');
/**
     * Tác dụng : customers
     *
     */
Route::prefix('customer')->group(function(){
          Route::get('login', 'CustomerAuth\LoginController@showLoginForm');
          Route::post('login', 'CustomerAuth\LoginController@login');
          Route::post('logout', 'CustomerAuth\LoginController@logout');
          Route::get('register', 'CustomerAuth\RegisterController@showRegistrationForm');
          Route::post('register', 'CustomerAuth\RegisterController@register');
          
     Route::middleware('customer.auth')->group(function(){
               Route::get('/test', function () {
                    return view('customer_auth.test');
          });
     });
});