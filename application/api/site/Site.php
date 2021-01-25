<?php

namespace app\api\site;

use app\admin\model\Site as SiteModel;
use app\admin\model\Theme;
use app\api\facade\Cypher;
use app\api\Http;

class Site extends APIBase
{
    public function __construct( SiteModel $siteModel )
    {
        parent::__construct($siteModel);
        $this->host = config('dictionary.site.host');
        $this->port = config('dictionary.site.port');
    }

    /**
     * 获取域名
     *
     * @param string $search
     * @return array
     */
    public function domain( string $search = '' ): array
    {
        $this->host = '192.168.8.211';
        $this->port = '80';
        $url        = $this->getUrl('domain/get_domain_v1');
        $user       = session('admin_user_name');
        $user       = 'zhengyaoyu';
        $res        = Http::curl($url, ['user' => $user, 'kw' => $search], 0, 'GET');
        if ( $res['code'] === 200 ) {
            $res['data'][] = 'h7c.cn';

            return $res['data'];
        }

        return [];
    }

    /**
     * 获取IP信息
     *
     * @param string $search
     * @return array|mixed
     * @throws \app\api\HttpError
     */
    public function ip( string $search )
    {
        $this->host = '192.168.8.211';
        $this->port = '80';
        $url        = $this->getUrl('domain/get_ip_v1');
        $user       = session('admin_user_name');
        $user       = 'zhangyaoyu';
        $res        = Http::curl($url, ['user' => $user, 'kw' => $search], 0, 'GET');
        if ( $res['code'] === 200 ) {
            $res['data'][] = '39.104.104.172';

            return $res['data'];
        }

        return [];
    }

    /**
     * 站点创建回调结果
     *
     * @param int    $id     站点ID
     * @param int    $status 创建状态 2 创建成功 3 创建失败
     * @param string $res    json 结果
     * @return array
     */
    public function createRes( $id, $status, $res )
    {
        $siteModelObj = SiteModel::where('site_id', $id)->find();
        if ( empty($siteModelObj) ) return modelReMsg(-1, '', '站点ID不正确');
        $siteModelObj->create_status = $status;
        $siteModelObj->create_result = $res;
        $siteModelObj->save();

        return modelReMsg(0, '', '接收成功');
    }

    /**
     * 创建站点
     *
     * @return array
     */
    public function create()
    {
        $site = $this->siteModelObj;
        if ( empty($site) ) return modelReMsg(-1, [], '站点未找到');
        $theme = Theme::where('theme_id', $site->temp_id)->find();
        if ( empty($theme) ) return modelReMsg(-1, [], '模版未找到');
        $this->host              = config('dictionary.site.host');
        $this->port              = config('dictionary.site.port');
        $this->path              = 'websiteManage';
        $url                     = $this->getUrl('build_v1/creat/');
        $argument                = config('dictionary.site.create');
        $param                   = [];
        $param['user']           = session('admin_user_name');
        $param['user']           = 'zhengyaoyu';
        $param['tocken']         = Cypher::encrypt($site->web_domain . '|' . time());
        $param['domain']         = $site->web_domain;
        $param['ip_detail']      = $site->ip;
        $param['w_template_url'] = $theme->temp_src;
        $param['siteId']         = $site->site_id;
        if ( $theme->is_h5 == 0 ) {
            $param['adaptive_domain'] = $site->m_domain;
            $param['m_template_url']  = $theme->m_temp_src;
        }
        $param += $argument;

        $res = Http::curl($url, $param);
        if ( $res['code'] == '200' ) {
            $site->create_status = 1;
            $site->save();

            return modelReMsg(0, [], '创建中...');
        }
        $site->create_status = 3;
        $site->save();

        return modelReMsg(-1, [], '创建失败');
    }

    /**
     * 删除站点
     */
    public function delete()
    {
        return true;
    }

    /**
     * 编辑站点
     */
    public function edit()
    {
        return true;
    }

    /**
     * 获取站点PV数
     *
     * @return array
     */
    public function pageViews()
    {
        return [];
    }

    /**
     *
     * @return array
     */
    public function pageCount()
    {
        return [];
    }

    /**
     * 获取站点PV数
     *
     * @return array
     */
    public function shoulu()
    {
        return [];
    }
}