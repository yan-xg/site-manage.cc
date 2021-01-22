<?php

namespace app\api\site;

use app\api\Http;
use tool\Log;

/**
 * 公共接口
 *
 * @package app\api\Common
 */
class Common extends APIBase
{
    protected $host = '192.168.9.10';

    protected $port = '9102';

    protected $path = 'api/register.php';

    /**
     * 公共js接口
     *
     * @param $param
     * @return array
     */
    public function commonJsModify( $param )
    {
        $url = $this->getUrl('commonJsModify');
        $res = Http::curl($url, $param, $this->header(), 'POST', true);
        if ( $res['status'] == 200 ) {
            return modelReMsg(0, '', '成功');
        }

        return modelReMsg(-1, '', '提交失败');
    }
}