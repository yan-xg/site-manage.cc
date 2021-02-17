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

    /**
     * 公共js获取
     *
     * @return array
     * @throws \app\api\HttpError
     */
    public function getCommonJs()
    {
        $url = $this->getUrl('getCommonJs');
        $res = Http::curl($url, '', $this->header(), 'GET');
        if ( $res['status'] == 200 ) {
            return $res['data'];
        }

        return ['cfg_bdtjjs' => '', 'cfg_bottomjs' => '', 'cfg_topjs' => '', 'cfg_dianhua' => '', 'cfg_laiyuan' => '', 'cfg_msiteid' => '', 'cfg_siteid' => '', 'cfg_xiangmu' => ''];
    }

    /**
     * 更新首页
     *
     * @param $data
     * @return false|string
     * @throws \app\api\HttpError
     */
    public function updateIndex( $data )
    {
        $url = $this->site($data['site_id'])->getUrl('updateIndex');
        $res = Http::curl($url, '', $this->header(), 'GET');

        return json_encode($res);
    }

    /**
     * 更新栏目
     *
     * @param $data
     * @return false|string
     * @throws \app\api\HttpError
     */
    public function updateColumn( $data )
    {
        $url = $this->site($data['site_id'])->getUrl('updateColumn');
        $res = Http::curl($url, '', $this->header(), 'GET');

        return json_encode($res);
    }


    /**
     * 更新内容
     *
     * @param $data
     * @return false|string
     * @throws \app\api\HttpError
     */
    public function updateArticle( $data )
    {
        $url = $this->site($data['site_id'])->getUrl('updateArchives');
        $res = Http::curl($url, '', $this->header(), 'GET');

        return json_encode($res);
    }
}