<?php

namespace app\admin\controller;

use app\api\site\Common as CommonAPI;
use app\admin\model\Site as SiteModel;
use think\App;

class Common extends Base
{
    protected $commonAPI;

    protected $siteModel;

    public function __construct( App $app = null, CommonAPI $commonAPI, SiteModel $siteModel )
    {
        parent::__construct($app);
        $this->commonAPI = $commonAPI;
        $this->siteModel = $siteModel;
    }

    /**
     * 验证站点信息
     *
     * @param $siteId
     */
    public function validateSite( $siteId )
    {
        if ( empty($siteId) ) exit('不合法ID');
        $site = $this->siteModel->find($siteId);
        if ( empty($site) ) exit('不合法ID');
    }

    public function index()
    {
        if ( request()->isPost() ) {

            $param  = input('post.');
            $siteId = $param['site_id'];
            unset($param['site_id']);
            $site = $this->siteModel->find($siteId);
            if ( empty($site) ) exit('不合法ID');
            $data['cfg_bdtjjs']      = $param['tongji'];
            $data['cfg_bottomjs']    = $param['head_code'];
            $data['cfg_topjs']       = $param['footer_code'];
            $data['cfg_dianhua']     = $param['dianhua'];
            $data['cfg_laiyuan']     = $param['laiyuan'];
            $data['cfg_msiteid']     = $param['hj_m_site_id'];
            $data['cfg_siteid']      = $param['hj_site_id'];
            $data['cfg_xiangmu']     = $param['xiangmu'];
            $data['cfg_webname']     = $site->name;
            $data['cfg_keywords']    = $param['keywords'];
            $data['cfg_description'] = $param['description'];
            $site->tongji            = $param['tongji'];
            $site->head_code         = $param['head_code'];
            $site->footer_code       = $param['footer_code'];
            $site->dianhua           = $param['dianhua'];
            $site->laiyuan           = $param['laiyuan'];
            $site->hj_m_site_id      = $param['hj_m_site_id'];
            $site->hj_site_id        = $param['hj_site_id'];
            $site->xiangmu           = $param['xiangmu'];
            $site->keywords          = $param['keywords'];
            $site->description       = $param['description'];
            $site->save();
            $res = $this->commonAPI->site($siteId)->commonJsModify($data);

            return json($res);
        }
        $id = input('param.site_id');
        $this->validateSite($id);
        $res  = $this->commonAPI->site($id)->getCommonJs();
        $data = [
                'tongji'       => $res['cfg_bdtjjs'],
                'head_code'    => $res['cfg_bottomjs'],
                'footer_code'  => $res['cfg_topjs'],
                'dianhua'      => $res['cfg_dianhua'],
                'laiyuan'      => $res['cfg_laiyuan'],
                'hj_m_site_id' => $res['cfg_msiteid'],
                'hj_site_id'   => $res['cfg_siteid'],
                'xiangmu'      => $res['cfg_xiangmu'],
                'keywords'     => $res['cfg_keywords'],
                'description'  => $res['cfg_description'],
        ];
        $this->assign(['data' => $data, 'siteId' => $id]);

        return view('edit');
    }
}
