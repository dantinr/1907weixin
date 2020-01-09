<?php
namespace App\Tools;
use Illuminate\Support\Facades\Cache;
//微信核心类 
class Wechat 
{   
    const appId = "wxb554e10dd1bd7e83";
    const appSerect =  "5853bddc626c4320ec0fcdb91d7620a4";
    //
    /**
     * 回复文本消息
     * @return [type] [description]
     */
    public static function reponseText($xmlObj,$msg)
    {
        echo "<xml>
        <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
        <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
        <CreateTime>".time()."</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[".$msg."]]></Content>
        </xml>";die;
    }
    /** 
     * 获取微信接口调用凭证 access_token
    */
    public static function getAccessToken()
    {
        //先判断缓存是否有数据 
        $access_token = Cache::get('access_token');
        //有数据之间返回 
        if(empty($access_token)){
            //获取access_token (微信接口调用凭证)
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Self::appId."&secret=".Self::appSerect;
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $access_token = $data['access_token'];  //token如何存储2小时 ???

            Cache::put('access_token',$access_token, 7200); // 120 分钟
        }
        //没有数据再进去调微信接口获取 =》 存入缓存
        return $access_token;
    }
    
    /**
     * @description: 
     * @param： String 用户openid 
     * @return: Array  用户基本信息
     */
    public static function getUserInfoByOpenId($openid)
    {   
        //获取token
        $access_token = Self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN
            ";
        $data = file_get_contents($url);
        $data = json_decode($data,true);
        return $data;
    }

    /**
     * 上传素材接口
     * @return [type] [description]
     */
    public static function uploadMedia()
    {
        $access_token = Self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$data['media_format'];
        $filePathObj = new \CURLFile(public_path()."/".$filePath);  //curl发送文件需要先通过CURLFile类处理
        //var_dump($filePath);die;
        $postData = ['media'=>$filePathObj];
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
    }
}
