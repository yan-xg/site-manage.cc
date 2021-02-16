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
     * @return json
     **/
    public function updateIndex( $data )
    {
        $url     = $data['web_domain'] . DIRECTORY_SEPARATOR . $this->path . '/updateIndex';
        $headers = $this->header();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);          //执行并存储结果
        curl_close($ch);

        return $content;
    }

    /**
     * 更新栏目
     *
     * @param $data
     * @return json
     **/
    public function updateColumn( $data )
    {
        $url     = $data['web_domain'] . DIRECTORY_SEPARATOR . $this->path . '/updateColumn';
        $headers = $this->header();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);          //执行并存储结果
        curl_close($ch);

        return $content;
    }


    /**
     * 更新内容
     *
     * @param $data
     * @return json
     **/
    public function updateArticle( $data )
    {
        $url     = $data['web_domain'] . DIRECTORY_SEPARATOR . $this->path . '/updateArchives';
        $headers = $this->header();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);          //执行并存储结果
        curl_close($ch);

        return $content;
    }
}