<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Curl;
class IndexController extends Controller
{
    //后台首页
    public function index()
    {
        return view("admin.index");
    }

    /**
     * 天气图表
     * @return [type] [description]
     */
    public function weather()
    {
    	return view("admin.weather");
    }

    /**
     * 获取天气数据
     * @return [type] [description]
     */
    public function getWeather(Request $request)
    {   
        //接城市名
        $city = $request->input("city");
        //调用天气接口获取一周天气数据
        $url = "http://api.k780.com:88/?app=weather.future&weaid=".$city."&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
        $data = Curl::get($url);
        
        return $data;
    }
}
