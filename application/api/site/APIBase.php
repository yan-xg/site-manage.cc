<?php

namespace app\api\site;

use app\api\Http;
use app\admin\model\Site as SiteModel;

class APIBase
{
    protected $scheme = 'http';

    protected $host = '192.168.8.211';

    protected $path = '';

    protected $port = '80';

    protected $siteModel;

    protected $siteModelObj;

    public function __construct( SiteModel $siteModel )
    {
        $this->siteModel = $siteModel;
    }

    /**
     * 获取接口验证签名
     *
     * @return array
     */
    public function header(): array
    {
        if ( config('dictionary.web.header') === false ) {
            return [];
        }
        $url = $this->getUrl('getSignature');
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['vision:1.0.0', 'token:XKyhVA3RsaWIRnAz']);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ( $result['status'] == 200 ) {
            return ['vision:1.0.0', 'token:XKyhVA3RsaWIRnAz', 'sign:' . $result['data']['signature']];
        };

        return [];
    }

    /**
     * 获取请求地址
     *
     * @param string $match
     * @return string
     */
    protected function getUrl( string $match ): string
    {
        return sprintf('%s://%s:%s/%s/%s', $this->scheme, $this->host, $this->port, $this->path, $match);
    }

    /**
     * 获取主机地址
     *
     * @return string
     */
    public function getHost()
    {
        return sprintf('%s://%s:%s', $this->scheme, $this->host, $this->port);
    }

    /**
     * 设置站点信息
     *
     * @param int $siteId
     * @return $this
     */
    public function site( int $siteId )
    {
        $this->siteModelObj = $this->siteModel->where('site_id', $siteId)->find();
        $host               = explode('.', $this->siteModelObj->web_domain);
        if ( count($host) < 3 ) array_unshift($host, 'www');

        $this->host = implode('.', $host);

        return $this;
    }
}