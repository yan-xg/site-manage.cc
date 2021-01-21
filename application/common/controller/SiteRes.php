<?php

namespace app\common\controller;

use app\api\facade\Site;
use think\Controller;
use think\Request;

class SiteRes extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $ip     = input('post.ip');
        $status = input('post.status');
        $res    = input('post.result');
        $result = Site::createRes($ip, $status, $res);
        if ( $result )
            return json(['code' => 200, 'msg' => '接收成功']);

        return json(['code' => 200, 'msg' => '接收成功']);
    }
}