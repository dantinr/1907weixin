<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\Media;
class MediaController extends Controller
{
    //sdfsdf
    //素材添加
    public function add()
    {
        ///echo Wechat::getAccessToken();die;
        return view("media.add");
    }

    public function add_do(Request $request)
    {
        //接值
        $data = $request->input();
        //文件上传
        $file = $request->file;
        $ext = $file->getClientOriginalExtension();  //得到文件后缀名
        $filename = md5(uniqid()).".".$ext;
        $path = $request->file->storeAs('images',$filename);
        //调接口
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$data['media_format'];
        $filePathObj = new \CURLFile(public_path()."/".$path);  //curl发送文件需要先通过CURLFile类处理
        //var_dump($filePath);die;
        $postData = ['media'=>$filePathObj];
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        $media_id = $res['media_id'];  //微信返回的素材id
        //入库
        Media::create([
            'media_name'=>$data['media_name'],
            'media_format'=>$data['media_format'],
            'media_type'=>$data['media_type'],
            'media_url'=>$path,  //素材上传地址
            'wechat_media_id'=>$media_id,
            'add_time'=>time(),
        ]);


    }


    public function show()
    {
        $data = Media::get()->toArray();

    	return view("media.show",[
            'data'=>$data
        ]);
    }
}
