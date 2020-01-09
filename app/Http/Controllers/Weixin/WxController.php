<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WxController extends Controller
{


    public function check()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = '2259b56f5898cd6192c50';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            //echo $_GET['echostr'];
        }else{
            return false;
        }


        //接收 微信推送的数据
        //$data = json_encode($_POST);
        $data = file_get_contents("php://input");
        $log_str = date("Y-m-d H:i:s")  . $data . "\n\n";
        file_put_contents('wx.log',$log_str,FILE_APPEND);

    }
}
