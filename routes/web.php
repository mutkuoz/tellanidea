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
    return redirect()->route('home');
});

Auth::routes();

Route::post('changeUser', 'HomeController@changeActingUser')->name('changeUser')->middleware('auth');

Route::resource('customers', 'CustomerController', ['only' => [
    'index']])->middleware('auth');

Route::get('customers/index2','CustomerController@index2')->middleware('auth');

Route::resource('kpis', 'KpiController')->middleware('admin');
Route::get('kpis/{id}/runCalculationQuery','KpiController@runCalculationQuery')->middleware('admin');
Route::post('kpis/{id}/runCalculationQuery','KpiController@runCalculationQuery')->middleware('admin');
Route::get('myPeople','KpiController@compareKPIs')->middleware('auth');

Route::resource('users', 'UserController')->middleware('admin');
Route::post('users/update/{id}','UserController@Update')->name('users.update')->middleware('admin');

Route::get('quotations/new','QuotationController@new')->middleware('auth');

Route::resource('strategies', 'StrategyController')->middleware('auth');

Route::get('accountplans', 'AccountPlanController@index')->middleware('auth');
Route::get('accountplans/showplanof/{id}', 'AccountPlanController@showPlanOf')->middleware('auth');
Route::get('accountplans/valuesimulator', 'AccountPlanController@valueSimulator')->middleware('auth');

Route::get('accountplans/addActions', 'AccountPlanController@addActions')->middleware('auth');
Route::post('accountplans/addActions', 'AccountPlanController@addActions')->middleware('auth');
Route::post('accountplans/addActionsWithAnalytics', 'AccountPlanController@addActionsWithAnalytics')->middleware('auth');
Route::post('accountplans/countOfActionsWithAnalytics', 'AccountPlanController@countOfActionsWithAnalytics')->middleware('auth')->name('countOfActionsWithAnalytics');
Route::post('accountplans/getMinThresholdWithAnalytics', 'AccountPlanController@minThresholdWithAnalytics')->middleware('auth')->name('minThresholdWithAnalytics');


Route::post('accountplans/addManualActions', 'AccountPlanController@addManualActions')->middleware('auth');
Route::get('accountplans/actionFunnel', 'AccountPlanController@showActionFunnel')->middleware('auth')->name('actionFunnel');


Route::post('opportunities/saveopportunities','OpportunityController@SaveOpportunities')->name('saveOpportunities')->middleware('auth');
//Route::resource('accountplans', 'AccountPlanController')->middleware('auth');

//Route::get('/createNewUser', array('before' => 'auth', 'uses' => 'HomeController@createNewUser'));

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'AdminController@index')->name('admin');

//Route::get('/admin/kpis', 'AdminController@kpis')->name('admin');
//Route::get('/admin/calculateKPIs', 'AdminController@calculateKPIs');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/products/getProductList','ProductController@getProductList')->name('getProductList')->middleware('auth');
