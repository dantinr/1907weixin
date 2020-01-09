<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\Media;
use App\Model\Channel;
class ChannelController extends Controller
{	
	/**
	 * 渠道展示
	 * @return [type] [description]
	 */
	public function show()
	{
		$data = Channel::get()->toArray();

		return view("channel.show",[
            'data'=>$data
        ]);
	}

	/**
	 * 渠道添加
	 */
	public function add()
	{	
		return view("channel.add");
	}

	public function add_do(Request $request)
	{
		//接值
		$channel_name = $request->input("channel_name");
		$channel_status =  $request->input("channel_status");
		//调用 微信生成带参数二维码接口
		$access_token = Wechat::getAccessToken();
		//地址
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
		//参数
		// $postData = '{"expire_seconds": 2592000, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$channel_status.'"}}}';
		$postData = [
			'expire_seconds'=>2592000,
			'action_name'=>"QR_STR_SCENE",
			'action_info'=>[
				'scene'=>[
					'scene_str'=>$channel_status
				],
			],
		];
		$postData = json_encode($postData,JSON_UNESCAPED_UNICODE);  //默认会把中文转码 unicode
		//echo $postData;die;
 		//发请求
		$res = Curl::post($url,$postData);
		$res = json_decode($res,true);
		$ticket = $res['ticket'];

		//入库
		Channel::create([
			'channel_name'=>$channel_name,
  	        'channel_status'=>$channel_status,
  	        'ticket'=>$ticket,
		]);
	}


	/**
	 * 本地
	 * @return [type] [description]
	 */
	public function charts()
	{	
		//数据统计图表
		$data = Channel::get()->toArray();

		//var_dump($data);die;
		$xStr = "";  //'苹果', '香蕉', '橙子'  &quot;视频推广&quot;,&quot;地铁推广&quot;
		$yStr = "";
		foreach ($data as $key => $value) {
			$xStr .= '"'.$value['channel_name'].'",';
			$yStr .= $value['num'].',';
		}
		$xStr = rtrim($xStr,",");
		$yStr = rtrim($yStr,",");
		//echo $xStr;die;
		return view("channel.charts",[
			'xStr' =>$xStr,
			'yStr'=>$yStr
		]);
	}

}
