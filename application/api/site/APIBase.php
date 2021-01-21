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

    public function header()
    {
        $this->path = 'api/register.php';
        $url        = $this->getUrl('getSignature');
        $res        = Http::curl($url, [], ['vision:1.0.0', 'token:XKyhVA3RsaWIRnAz'], 'HEAD');
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
        $this->host         = $this->siteModelObj->web_domain;

        return $this;
    }
}