<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Wechat;
class WechatController extends Controller
{   
    private $student = [
        '陈香菲',
        '张攀峰',
        '张少杰',
        '刘京婧',
        '王梓东',
        '吕心',
        '李倩倩',
        '张孟洋',
        '范培轩',
        '张怡静',
        '王梓晨',
        '王禹尧',
        '翟成厉',
        '刘赫',
        '关天龙',
        '施帅波',
        '史佳奇',
        '郭自文',
        '度国伟',
        '商业兴',
        '胡彩龙',
        '文安生',
        '高泽东',
        '刘清源',
        '王振国',
        '刘奕岑',
        '陈广通',
        '陈恩鹏',
        '刘伟晨',
        '薛彬英',
        '刘波',
        '牛群',
        '孙佳豪',
        '霍佳盛',
        '万阳',
        '范相宾',
        '张广进',
        '陈亚涛',
        '滕乙嘉',
        '马子正',
    ];
    //微信开发者配置服务器
    public function index(Request $request)
    {   
        //echo 1;die;
        //微信接入 
        // $echostr = $request->input('echostr');die;
        //微信关注回复
        //接入完成之后，微信公众号内用户任何操作  微信服务器=》POST形式XML格式 发送到配置的url上
        $xml = file_get_contents("php://input"); //接收原始的xml或json数据流 
        //写文件里
        file_put_contents("log.txt","\n\n".$xml."\n",FILE_APPEND);

        //方便处理 xml =》 对象 
        $xmlObj = simplexml_load_string($xml);
        //如果是关注 
        if($xmlObj->MsgType == 'event' && $xmlObj->Event == 'subscribe'){
            //关注时 获取用户基本信息 
            $data = Wechat::getUserInfoByOpenId($xmlObj->FromUserName);
            //得到渠道的标识 
            //$eventKey = $xmlObj->EventKey; //截取字符串 qrscene_111
            $channel_status = $data['qr_scene_str'];  //111
            //根据渠道标识 关注人数递增
            //UPDATE xx SET xxx = xx + 1 WHERE x = 1;
            \DB::table('channel')->where(['channel_status'=>$channel_status])->increment('num');
            //存入用户基本信息 - 渠道标识
            //判断用户基本信息表 有没有数据 （通过opendi查询表）
            //判断 有 修改状态,修改渠道号
            //     没有 添加
            WecahtUser::create([
                'openid'=>$data['openid'],
                'openid'=>$data['openid'],
                'channel_status'=>$data['channel_status'],
            ]);
            //
            $nickname = $data['nickname']; //取到用户昵称
            $msg = "欢迎".$nickname."关注";
            //回复文本消息
            Wechat::reponseText($xmlObj,$msg);
        }


        //如果用户取关 
        if($xmlObj->MsgType == 'event' && $xmlObj->Event == 'unsubscribe'){
            
            //用户基本信息表 修改状态 
            WecahtUser::where(['openid'=>$xmlObj->FromUserName])->update(['is_del'=>0]);
            //得到渠道标识
            //查询用户信息表 通过openid $xmlObj->FromUserName
            
            //渠道表统计人数-1
            //
        }

        //如果是用户发送文本消息
        if($xmlObj->MsgType == 'text'){
            //得到用户发送内容
            $content = trim($xmlObj->Content);
            if($content == '1'){
                //回复本班全部学生姓名
                $msg = implode(",",$this->student);
                //回复文本消息
                Wechat::reponseText($xmlObj,$msg);
            }elseif($content =='2'){
                //随即回复一个最帅同学 
                shuffle($this->student);
                //var_dump($student);die;
                $msg = $this->student[0];
                //回复文本消息
                Wechat::reponseText($xmlObj,$msg);
            }elseif(mb_strpos($content,"天气") !== false){   //城市名字+天气
                //回复天气
                $city = rtrim($content,"天气");
                if(empty($city)){
                    $city = "北京";
                }
                //调用k780天气接口 获取数据
                $url = "http://api.k780.com:88/?app=weather.future&weaid=".$city."&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
                //调用接口 （GET POST）
                //发送请求 打开文件  接受xml数据
                $data = file_get_contents($url);
                $data = json_decode($data,true);
                //2019-12-31 星期二 北京 -3℃/-10℃
                //2019-12-31 星期二 北京 -3℃/-10℃
                //2019-12-31 星期二 北京 -3℃/-10℃
                //2019-12-31 星期二 北京 -3℃/-10℃
                $msg = "";
                foreach ($data['result'] as $key => $value) {
                    $msg .= $value['days']." ".$value['week']." ".$value['citynm']." ".$value['temperature']."\n";
                }	
                Wechat::reponseText($xmlObj,$msg);
            }
        }

        //如果是用户发送图片消息
        if($xmlObj->MsgType == 'image'){
            //完成斗图功能 （从随即库随即回复图片）
            //
            //select * from media ORDER BY rand() DESC  LIMIT 1
            
            //回复图片
            echo "<xml>
              <ToUserName><![CDATA[toUser]]></ToUserName>
              <FromUserName><![CDATA[fromUser]]></FromUserName>
              <CreateTime>12345678</CreateTime>
              <MsgType><![CDATA[image]]></MsgType>
              <Image>
                <MediaId><![CDATA[media_id]]></MediaId>
              </Image>
            </xml>";die;
        }
    }

    
}
