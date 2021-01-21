<?php

namespace app\common\lib;

use think\Controller;
use think\Request;

class Upload extends Controller{

    /**
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
}