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