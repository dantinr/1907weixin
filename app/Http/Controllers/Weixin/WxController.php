<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use App\Model\WeiXinModel;
use Illuminate\Http\Request;

class WxController extends Controller
{

    protected $access_token;

    public function __construct()
    {
        //$this->access_token = WeiXinModel::getAccessToken();
    }

    public function check()
    {
////        $signature = $_GET["signature"];
////        $timestamp = $_GET["timestamp"];
////        $nonce = $_GET["nonce"];
//
//        $token = '2259b56f5898cd6192c50';
//        $tmpArr = array($token, $timestamp, $nonce);
//        sort($tmpArr, SORT_STRING);
//        $tmpStr = implode( $tmpArr );
//        $tmpStr = sha1( $tmpStr );
//
//        if( $tmpStr == $signature ){
//            //echo $_GET['echostr'];
//        }else{
//            return false;
//        }


        //接收 微信推送的数据
        //$data = json_encode($_POST);
        $data = file_get_contents("php://input");
        $log_str = date("Y-m-d H:i:s") . $data . "\n\n";
        file_put_contents('wx.log', $log_str, FILE_APPEND);

        $xml_obj = simplexml_load_string($data);


        $openid = $xml_obj->FromUserName;       // 取openid
        $msg_type = $xml_obj->MsgType;          // 消息类型
        $media_id = $xml_obj->MediaId;           // MediaId


        if ($msg_type == 'image')          //  图片
        {
            // 下载图片
            $this->downloadImg($media_id);

        } elseif ($msg_type == 'video')        // 视频
        {
            //下载视频
            $this->downloadVideo($media_id);
        }
    }


    /**
     * 下载图片素材
     */
    protected function downloadImg($media_id)
    {

        $access_token = WeiXinModel::getAccessToken();
        //var_dump($access_token);
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $access_token . '&media_id=' . $media_id;

        //请求获取素材接口
        $img = file_get_contents($url);
        //var_dump($img);

        //保存图片
        file_put_contents("bbb.jpg", $img);

    }

    protected function downloadVideo($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->access_token . '&media_id=' . $media_id;

        //请求获取素材接口
        $img = file_get_contents($url);

        //保存视频
        $file_name = date("YmdHis") . rand(1111, 9999) . '.mp4';
        $res = file_put_contents($file_name, $img);
        var_dump($res);

    }


    /**
     * 根据Openid群发
     */
    public function sendAllByOpenId()
    {
        $users = WeiXinModel::select('openid')->get()->toArray();
        //echo '<pre>';print_r($users);echo '</pre>';die;
        $openid_list = array_column($users,'openid');
        echo '<pre>';print_r($openid_list);echo '</pre>';
        // openid 列表  可以从数据库表获取


        $msg = date("Y-m-d H:i:s")  . " 再发两条 ： 马上放寒假了，不要忘记做作业!!";

        echo "消息： ".$msg;echo '</br>';
        $json_data = [
            "touser"    => $openid_list,
            "msgtype"   => "text",
            "text"      => [
                "content"   => $msg
            ]
        ];

        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->access_token;

        $response = WeiXinModel::curlPost($url,$json_data);
        // 检查错误
        if($response['errcode'] > 0){
            echo '错误信息： ' . $response['errmsg'];
        }else{
            echo "发送成功";
        }

    }

    public function test()
    {
        $appid = env('WX_APPID');
        $redirect_uri = urlencode(env('WX_AUTH_REDIRECT_URI'));
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        echo $url;
    }

    /**
     * 接收网页授权code
     */
    public function auth()
    {
        // 接收 code
        $code = $_GET['code'];
        //换取access_token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_APPSEC').'&code='.$code.'&grant_type=authorization_code';
        $json_data = file_get_contents($url);
        $arr = json_decode($json_data,true);
        echo '<pre>';print_r($arr);echo '</pre>';


        // 获取用户信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
        $json_user_info = file_get_contents($url);
        $user_info_arr = json_decode($json_user_info,true);
        echo '<pre>';print_r($user_info_arr);echo '</pre>';


    }
}
