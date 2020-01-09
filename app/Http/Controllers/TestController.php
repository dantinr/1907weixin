<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{

    public function getAccessToken()
    {
        $redis_weixin_token_key = 'weixin_access_token';
        //判断是否有缓存
        $token = Redis::get($redis_weixin_token_key);

        if($token){
            // TODO
            return $token;
        }else{
            //获取 微信 access_token
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx86737d80f8dac5c9&secret=7c5deccfb30731052d11734a6d21f5a0';
            //请求接口
            $json = file_get_contents($url);
            $arr = json_decode($json,true);
            $token = $arr['access_token'];
        }

        //缓存token]
        Redis::set($redis_weixin_token_key,$token);
        Redis::expire($redis_weixin_token_key,3600);
        return $token;

    }
}
