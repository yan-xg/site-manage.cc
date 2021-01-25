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

        return ['cfg_bdtjjs' => '', 'cfg_bottomjs' => '', 'cfg_topjs' => ''];
    }

    /**
     * 获取sign
     */
    public function getSign(){
        $url = 'http://192.168.9.10:9102/api/register.php/getSignature';//获取sign

        $headers = [
            'vision:1.0.0',
            'token:XKyhVA3RsaWIRnAz'
        ];
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出

        //curl_setopt($ch, CURLOPT_POST, 1);      //发送POST类型数据
        //curl_setopt($ch, CURLOPT_POSTFIELDS, ['data' => $data]); //POST数据，$post可以是数组，也可以是拼接

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $content = curl_exec($ch);          //执行并存储结果

        curl_close($ch);

        $content = json_decode($content,true);
        if($content['status']==200){
            return $content['data']['signature'];
        }

    }


    /**
     * 更新首页
     * @param $data
     * @return json
    **/
    public function updateIndex( $data ){
        $sign = $this->getSign();

//        $url = 'http://192.168.9.10:9102/api/register.php/updateIndex';
        $url = $data['web_domain'].'/register.php/updateIndex';
        $headers = [
            'vision:1.0.0',
            'token:XKyhVA3RsaWIRnAz',
            'sign:'.$sign
        ];

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
     * @param $data
     * @return json
     **/
    public function updateColumn( $data ){
        $sign = $this->getSign();

//        $url = 'http://192.168.9.10:9102/api/register.php/updateColumn';
        $url = $data['web_domain'].'/register.php/updateColumn';
        $headers = [
            'vision:1.0.0',
            'token:XKyhVA3RsaWIRnAz',
            'sign:'.$sign
        ];

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
     * @param $data
     * @return json
     **/
    public function updateArticle( $data ){
        $sign = $this->getSign();

//        $url = 'http://192.168.9.10:9102/api/register.php/updateArchives';
        $url = $data['web_domain'].'/register.php/updateArchives';
        $headers = [
            'vision:1.0.0',
            'token:XKyhVA3RsaWIRnAz',
            'sign:'.$sign
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);          //执行并存储结果
        curl_close($ch);
        return $content;
    }
}