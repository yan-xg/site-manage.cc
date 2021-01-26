<?php

namespace app\common\controller;

use app\api\facade\Cypher;
use app\api\facade\Site;
use think\Controller;
use think\facade\Log;

class SiteRes extends Controller
{
    protected $siteModel;

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $id    = input('post.siteId');
        $res   = input('post.result');
        $token = input('post.tocken');
        if ( !Cypher::validate('6830b3f8b3f7f02e64d4239003bb18fe', $token) )
            return json(['code' => -1, 'msg' => '验证失败']);
        $result = json_decode($res, true);
        $status = 2;
        if ( $result['data']['local_build_code'] == 0 || $result['data']['online_build_code'] == 0 ) {
            $status = 3;
        }
        $result = Site::createRes($id, $status, $res);
        if ( $result['code'] == 0 )
            return json(['code' => 200, 'msg' => '接收成功']);

        return json(['code' => $result['code'], 'msg' => $result['msg']]);
    }
}
