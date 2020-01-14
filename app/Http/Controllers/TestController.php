<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{


    /**
     * 获取最新access_token 并换缓存
     */
    public function freshToken()
    {

        $redis_weixin_token_key = 'weixin_access_token';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx86737d80f8dac5c9&secret=7c5deccfb30731052d11734a6d21f5a0';
        //请求接口
        $json = file_get_contents($url);
        $arr = json_decode($json,true);
        $token = $arr['access_token'];

        //缓存token]
        Redis::set($redis_weixin_token_key,$token);
        Redis::expire($redis_weixin_token_key,3600);
        echo "token已刷新 " . date("Y-m-d H:i:s");echo '</br>';
        echo $token;
    }


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


    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $access_token = $this->getAccessToken();
        $openid = 'oLreB1h8OASPn6d5jKuRxodhjgLE';

        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $json = file_get_contents($url);
        $arr = json_decode($json,true);
        echo '<pre>';print_r($arr);echo '</pre>';
    }


    //创建菜单
    public function createMenu()
    {
        //echo date("Y-m-d H:i:s");echo '</br>';
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->getAccessToken();
        //echo $url;echo '</br>';
        $menu = [
            "button"    => [
                [
                    "type"  => "view",
                    "name"  => "签到",
                    "url"   => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx86737d80f8dac5c9&redirect_uri=http%3A%2F%2F1907wx.comcto.com%2Fwx%2Fauth&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
                ],
//                [
//                    "name"  => "二级菜单",
//                    "sub_button"    => [
//                        [
//                            "type"  => "scancode_push",
//                            "name"  => "扫一扫",
//                            "key"   => "scan111"
//                        ],
//                        [
//                            "type"  => "pic_sysphoto",
//                            "name"  => "拍照",
//                            "key"   => "photo111"
//                        ]
//                    ]
//                ],
            ]
        ];

        $this->curlPost($url,$menu);
    }



    protected function curlPost($url,$menu)
    {
        $ch = curl_init();

        $data_string = json_encode($menu,JSON_UNESCAPED_UNICODE);     //要发送的数据

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
        $arr = json_decode($output,true);
        echo '<pre>';print_r($arr);echo '</pre>';
    }



    public function postman()
    {

        //随机回复图片
        $imgs = [
            "wT9i0u8dPu6TQrdPRvbF08tAFzBizZ_PBTS9aycUIk80z_QURYMKAkeT5XIYf3Ak",
            "ol96Uexm2AFhMawLG999PcTd6-XU4KFQq7nAPFHBbLLGHxIx6BTfIbO0-mCAeqns",
            "kxSNJtm6e3rszYThL5EldYVvRaRU9H5DgvP9NyZNw5arwKgeftjv_p3XfMNVVvh5",
            "CZxjQk-jR_yKqLSzrabr5ARnHg6dljw5krYKSmYTPNBHqj9L25Wmvx_KFd2SFh4Z"
        ];

        echo '<pre>';print_r($imgs);echo '</pre>';
        //随机数组
        $i = array_rand($imgs);
        echo 'i: '.$i;echo '</br>';
        echo $imgs[$i];die;


        //echo '<pre>';print_r($_POST);echo '</pre>'; //可以接收 form-data x-www-form-urlencoded
        $data = file_get_contents("php://input");   // 接收 raw 数据  json 或 xml
        echo $data;
        //$data = json_decode($json);     // 将 json字符串转为 php对象
        //$data = json_decode($data,true);     // 将 json字符串转为 php对象


        $xml_data = simplexml_load_string($data);
        echo '<pre>';print_r($xml_data);echo '</pre>';

        echo $xml_data->CreateTime;echo '</br>';
        echo $xml_data->MsgId;

    }


}
