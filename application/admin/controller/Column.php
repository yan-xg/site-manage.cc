<?php

namespace app\admin\controller;

use app\admin\model\Site as SiteModel;
use app\api\site\Column as ColumnAPI;
use app\admin\validate\ColumnValidate;
use think\App;

class Column extends Base
{
    protected $columnAPI;
    protected $columnValidate;
    protected $siteModel;

    /**
     * Column constructor.
     *
     * @param App|null       $app
     * @param ColumnValidate $columnValidate
     * @param ColumnAPI      $columnAPI
     * @param SiteModel      $siteModel
     */
    public function __construct( App $app = null, ColumnValidate $columnValidate, ColumnAPI $columnAPI, SiteModel $siteModel )
    {
        parent::__construct($app);
        $this->columnAPI      = $columnAPI;
        $this->columnValidate = $columnValidate;
        $this->siteModel      = $siteModel;
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


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        if ( !request()->isAjax() ) {
            $siteId = input('site_id');
            $this->validateSite($siteId);
            $this->assign('siteId', $siteId);

            return view('index');
        }

        $limit  = input('param.limit');
        $page   = input('param.page');
        $typeId = input('param.typeid');
        $siteId = input('param.site_id');

        $res = $this->columnAPI->site($siteId)->getColumnList(['typeId' => $typeId], $page, $limit, ['id' => 'desc']);

        return json(['code' => 0, 'msg' => 'ok', 'count' => $res['total'], 'data' => $res['rows']]);
    }

    /**
     * 创建栏目
     */
    public function create()
    {
        if ( request()->isPost() ) {
            $param = input('post.');
            if ( !$this->columnValidate->check($param) )
                return ['code' => -1, 'data' => '', 'msg' => $this->columnValidate->getError()];
            $siteId = $param['site_id'];
            unset($param['site_id']);
            $res = $this->columnAPI->site($siteId)->columnAdd($param);

            return json($res);
        }
        $siteId = input('site_id');
        $this->validateSite($siteId);
        $this->assign('siteId', $siteId);

        return view('add');
    }

    /**
     * 修改栏目
     */
    public function edit()
    {
        if ( request()->isPost() ) {
            $param = input('post.');
            if ( !$this->columnValidate->check($param) ) {
                return ['code' => -1, 'data' => '', 'msg' => $this->columnValidate->getError()];
            }
            $siteId = $param['site_id'];
            unset($param['site_id']);
            $res = $this->columnAPI->site($siteId)->columnModify($param);

            return json($res);
        }
        $siteId = input('site_id');
        $this->validateSite($siteId);
        $this->assign('siteId', $siteId);
        $id   = input('column_id');
        $data = $this->columnAPI->site($siteId)->getColumnOne($id);

        $this->assign(['data' => $data]);

        return view('edit');
    }

    /**
     * 删除栏目
     *
     * @param $id
     * @param $site_id
     * @return array|\think\response\Json
     * @throws \app\api\HttpError
     */
    public function delete( $id, $site_id )
    {
        if ( request()->isAjax() ) {
            if ( empty($id) )
                return ['code' => -1, 'data' => '', 'msg' => $this->columnValidate->getError()];
            $siteId = $site_id;
            $this->validateSite($siteId);

            $res = $this->columnAPI->site($siteId)->columnDel($id);

            return json($res);
        }

        $this->fetch();
    }
}
