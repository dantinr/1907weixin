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

Route::get('/info',function (){
    phpinfo();
});


Route::any('/test','TestController@postman');       //postman调试


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


//微信接入
Route::any('/wx','Weixin\WxController@check');    //微信接入

// 自动上线
Route::post('/gitpull','Git\IndexController@index');    // test 项目自动上线


Route::get('/wx/fresh_token','TestController@freshToken');    //刷新微信access_token
Route::get('/wx/token','TestController@getAccessToken');    //获取微信access_token
Route::get('/wx/u','TestController@getUserInfo');    //获取微信用户基本信息
Route::get('/wx/menu','TestController@createMenu');    //创建菜单

Route::get('/wx/sendmsg','Weixin\WxController@sendAllByOpenId');    //根据openid群发


// 微信网页授权
Route::get('/wx/test','Weixin\WxController@test');      // 测试
Route::get('/wx/auth','Weixin\WxController@auth');      // 接收 code





