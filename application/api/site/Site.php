<?php

namespace app\api\site;

use app\admin\model\Site as SiteModel;
use app\admin\model\Theme;
use app\api\Http;

class Site extends APIBase
{
    public function __construct( SiteModel $siteModel )
    {
        parent::__construct($siteModel);
        $this->host = config('dictionary.site.host');
    }

    /**
     * 获取域名
     *
     * @param string $search
     * @return array
     */
    public function domain( string $search = '' ): array
    {
        $url  = $this->getUrl('domain/get_domain_v1');
        $user = session('admin_user_name');
        $user = 'yangwei';
        $res  = Http::curl($url, ['user' => $user, 'kw' => $search], 0, 'GET');
        if ( $res['code'] === 200 ) {
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
        $url  = $this->getUrl('domain/get_ip_v1');
        $user = session('admin_user_name');
        $user = 'yangwei';
        $res  = Http::curl($url, ['user' => $user, 'kw' => $search], 0, 'GET');
        if ( $res['code'] === 200 ) {
            return $res['data'];
        }

        return [];
    }

    /**
     *
     * @param string $ip     IP地址
     * @param int    $status 状态码
     * @param array  $res    结果串
     * @return bool
     */
    public function createRes( $ip, $status, $res )
    {

        return true;
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

        $url                     = $this->getUrl('websiteManage/build_v1/creat/');
        $argument                = config('dictionary.site.create');
        $param                   = [];
        $param['user']           = session('admin_user_name');
        $param['tocken']         = md5('uio234...');
        $param['domain']         = $site->domain;
        $param['ip_detail']      = $site->ip;
        $param['w_template_url'] = $theme->temp_src;
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
        $site->create_status = 2;
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