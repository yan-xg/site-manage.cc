<?php

namespace app\admin\controller;

use app\api\site\Common;
use app\admin\model\Site as SiteModel;
use think\App;

class CommonJs extends Base
{
    protected $common;

    protected $siteModel;

    public function __construct( App $app = null, Common $common, SiteModel $siteModel )
    {
        parent::__construct($app);
        $this->common    = $common;
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
            $data['cfg_bdtjjs']   = $param['tongji'];
            $data['cfg_bottomjs'] = $param['head_code'];
            $data['cfg_topjs']    = $param['footer_code'];
            $site->tongji         = $param['tongji'];
            $site->head_code      = $param['head_code'];
            $site->footer_code    = $param['footer_code'];
            $site->save();
            $res = $this->common->site($siteId)->commonJsModify($data);

            return json($res);
        }
        $id = input('param.site_id');
        $this->validateSite($id);
        $res  = $this->common->site($id)->getCommonJs();
        $data = [
                'tongji'      => $res['cfg_bdtjjs'],
                'head_code'   => $res['cfg_bottomjs'],
                'footer_code' => $res['cfg_topjs'],
        ];
        $this->assign(['data' => $data, 'siteId' => $id]);

        return view('edit');
    }
}
