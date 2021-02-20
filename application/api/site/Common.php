<?php

namespace app\api\site;

use app\admin\model\Theme;
use app\api\Http;
use think\facade\Log;

/**
 * 公共接口
 *
 * @package app\api\Common
 */
class Common extends APIBase
{
    protected $path = 'api/register.php';


    public function getIsH5( $id, $url, $param, $type = 'GET' )
    {
        $theme = Theme::where('theme_id', $id)->find();
        if ( empty($theme) ) return false;
        if ( $theme->is_h5 == 0 ) {
            $url    = str_replace($this->host, $this->siteModelObj->m_domain, $url);
            $header = $this->header($this->siteModelObj->m_domain);

            return Http::curl($url, $param, $header, $type, true);
        }

        return ['status' => 200];
    }

    /**
     * 公共js接口
     *
     * @param $param
     * @return array
     */
    public function commonJsModify( $param )
    {
        $url  = $this->getUrl('commonJsModify');
        $res  = Http::curl($url, $param, $this->header(), 'POST', true);
        $res2 = $this->getIsH5($this->siteModelObj->temp_id, $url, $param, 'POST');
        if ( $res['status'] != 200 ) return modelReMsg(-1, '', '更新失败');
        if ( $res2['status'] != 200 ) return modelReMsg(-1, '', '移动端更新失败');

        return modelReMsg(0, '', '成功');
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
        $url  = $this->site($data['site_id'])->getUrl('updateIndex');
        $res  = Http::curl($url, '', $this->header(), 'GET');
        $res2 = $this->getIsH5($this->siteModelObj->temp_id, $url, '');

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
        $url  = $this->site($data['site_id'])->getUrl('updateColumn');
        $res  = Http::curl($url, '', $this->header(), 'GET');
        $res2 = $this->getIsH5($this->siteModelObj->temp_id, $url, '');

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
        $url  = $this->site($data['site_id'])->getUrl('updateArchives');
        $res  = Http::curl($url, '', $this->header(), 'GET');
        $res2 = $this->getIsH5($this->siteModelObj->temp_id, $url, '');

        return json_encode($res);
    }
}