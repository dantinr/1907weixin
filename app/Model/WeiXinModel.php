<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class WeiXinModel extends Model
{

    public $table = 'p_wx_users';
    public $primaryKey = 'uid';

    public static function getAccessToken()
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

    /**
     * CURL POST
     * @param $url
     * @param $json_data
     */
    public static function curlPost($url,$json_data)
    {
        $ch = curl_init();

        $data_string = json_encode($json_data,JSON_UNESCAPED_UNICODE);     //要发送的数据

        // 设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        //post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        //加入以下设置
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string))
        );
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        return json_decode($output,true);
    }

}
