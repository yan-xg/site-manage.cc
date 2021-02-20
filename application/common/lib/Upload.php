<?php

namespace app\common\lib;

require dirname(dirname(dirname(__DIR__))) . '/vendor/qiniu/autoload.php';

use think\Controller;
use think\Env;
use think\Request;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class Upload extends Controller{

    /**
     * 上传图片
     * @param null $file_path 保存的目录
     * @param null $form_name 表单中的名称
     * @return \think\response\Json
     */
    public function uploadOnePic($file_path = null,$form_name = 'file')
    {
        if (request()->isPost()){
            $file_path = $file_path ? config('app.upload_root_path') . $file_path : config('app.upload_root_path');
            if (!file_exists('.' . $file_path)) {
                mkdir('.' . $file_path, 0777,true);
            }
            $rules = [
                'ext'   => 'jpeg,jpg,png,gif',
                'size'  => 10 * 1024 * 1024,
            ];
            $file_info = request()->file($form_name)->validate($rules)->move('.'.$file_path);
            if (!$file_info){
                return json(['code'=>0,'msg'=>'格式仅支持jpeg,jpg,png,gif,最大图片为10Mb']);
            }
            $path = $file_info->getSaveName();
            $path = str_replace('\\','/',$path);
            $file_path .= $path;

            return json(['code'=>1,'msg'=>$file_path]);
        }else{
            return json(['code'=>0,'msg'=>'操作失误,请重新操作']);
        }
    }

    /**
     * 上传文件
     * @param null $file_path 保存的目录
     * @param null $form_name 表单中的名称
     * @param null $name 用于区分模板还是站点文件
     * @return \think\response\Json
    **/
    public function uploadZip($file_path = null,$form_name = 'file',$name=false){
        if (request()->isPost()){
            $rules = [
                'ext'   => 'zip,rar,7z',
                'size'  => 100 * 1024 * 1024,
            ];
            $file = request()->file($form_name);

            $file_info = $file->validate($rules);
            $filePath  = $file->getRealPath();
            $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
            // 上传到七牛后保存的文件名
            if($name==TRUE){
                $filename = $file->getInfo('name');
            }else{
                $filename = 'templets-'.substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999) . '.' . $ext;
            }

            if (!$file_info){
                return json(['code'=>0,'msg'=>'格式仅支持zip,rar,7z,最大文件为100Mb']);
            }

            $qiniu = config('dictionary.qiniu');
            $auth = new Auth($qiniu['accesskey'], $qiniu['secretkey']);
            $token = $auth->uploadToken($qiniu['bucket']);
            // 构建 UploadManager 对象
            $uploadMgr = new UploadManager();
            list($ret, $err) = $uploadMgr->putFile($token, $filename, $filePath);
            if ($err !== null) {
                return json(["err"=>1,"msg"=>$err,"data"=>""]);
            } else {
                //返回完整URL
                return json(["err"=>0,"msg"=>"上传完成","data"=>($qiniu['DOMAIN'] .'/'. $ret['key'])]);
            }
        }else{
            return json(['code'=>0,'msg'=>'操作失误,请重新操作']);
        }
    }
}