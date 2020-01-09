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
    return view('welcome');
});
//微信开发者配置服务器
Route::any('/wechat/index', 'WechatController@index');
Route::any('admin_login', 'Admin\LoginController@login');
//微信后台项目
Route::middleware([])->group(function(){
	//后台首页
	Route::any('admin_index', 'Admin\IndexController@index');
	Route::any('admin/weather', 'Admin\IndexController@weather');
	Route::any('admin/getWeather', 'Admin\IndexController@getWeather');
	//素材管理
	Route::any('media/show', 'Admin\MediaController@show');
	Route::any('media/add', 'Admin\MediaController@add');
	Route::any('media/add_do', 'Admin\MediaController@add_do');
	//渠道管理
	Route::any('channel/show', 'Admin\ChannelController@show');
	Route::any('channel/add', 'Admin\ChannelController@add');
	Route::any('channel/add_do', 'Admin\ChannelController@add_do');
	Route::any('channel/charts', 'Admin\ChannelController@charts');
});


//Route::get('/wx/token','');
