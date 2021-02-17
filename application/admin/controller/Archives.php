<?php

namespace app\admin\controller;

use app\api\facade\Column;
use app\api\site\Archives as ArchivesAPI;
use app\api\site\Column as ColumnAPI;
use app\admin\model\site as SiteModel;
use app\admin\validate\ArchivesValidate;
use think\App;

class Archives extends Base
{
    protected $archivesAPI;
    protected $archivesValidate;
    protected $siteModel;

    /**
     * Archives constructor.
     *
     * @param App|null         $app
     * @param ArchivesValidate $archivesValidate
     * @param ArchivesAPI      $archivesAPI
     * @param SiteModel        $siteModel
     */
    public function __construct( App $app = null, ArchivesValidate $archivesValidate, ArchivesAPI $archivesAPI, SiteModel $siteModel )
    {
        parent::__construct($app);
        $this->archivesAPI      = $archivesAPI;
        $this->archivesValidate = $archivesValidate;
        $this->siteModel        = $siteModel;
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
        $typeid = input('param.typeid');
        $siteId = input('param.site_id');
        $img    = '<img src="%s" style="height:50px;" />';
        $res    = $this->archivesAPI->site($siteId)->getArchivesList(['typeId' => $typeid], $page, $limit, ['id' => 'desc']);
        $host   = $this->archivesAPI->site($siteId)->getHost();
        foreach ( $res['rows'] as &$v ) {
            $v['pubdate']  = date("Y-m-d H:i", $v['pubdate']);
            $v['senddate'] = date("Y-m-d H:i", $v['senddate']);
            $v['litpic']   = sprintf($img, $host . $v['litpic']);
        }

        return json(['code' => 0, 'msg' => 'ok', 'count' => $res['total'], 'data' => $res['rows']]);
    }

    /**
     * 创建文章
     */
    public function create()
    {
        if ( request()->isPost() ) {
            $param = input('post.');
            if ( !$this->archivesValidate->check($param) )
                return ['code' => -1, 'data' => '', 'msg' => $this->archivesValidate->getError()];

            $param['body'] = $this->convertPicture($param['body']);
            $siteId        = $param['site_id'];
            unset($param['site_id']);
            $res = $this->archivesAPI->site($siteId)->archivesAdd($param);

            return json($res);
        }
        $siteId = input('site_id');
        $this->validateSite($siteId);
        $this->assign('siteId', $siteId);

        return view('add');
    }

    /**
     * 将内容中加入host信息
     *
     * @param string $body
     * @param string $host
     * @return string
     */
    private function hostPicture( $body, $host )
    {
        $reg = "/(<img src=[\"|'])(.*?)([\"|'].*?>)/";
        preg_match_all($reg, $body, $matches);
        foreach ( $matches[2] as $k => $v ) {
            $search  = $matches[1][$k] . $matches[2][$k] . $matches[3][$k];
            $replace = sprintf('%s%s%s%s', $matches[1][$k], $host, $matches[2][$k], $matches[3][$k]);
            $body    = str_replace($search, $replace, $body);
        }

        return $body;
    }

    /**
     * 将内容中的图片base64增加
     *
     * @param string $body
     * @return string
     */
    private function convertPicture( $body )
    {
        $reg = "/(<img src=[\"|'])(.*?)([\"|'].*?>)/";

        preg_match_all($reg, $body, $matches);

        foreach ( $matches[2] as $k => $v ) {
            if ( strlen($v) > 300 ) {
                $search  = $matches[1][$k] . $matches[2][$k] . $matches[3][$k];
                $replace = sprintf('%s%s%s%s%s', $matches[1][$k], '$#!#$', str_replace(['\r\n'], '', $matches[2][$k]), '$#!#$', $matches[3][$k]);
                $body    = str_replace($search, $replace, $body);
            }
        }

        return $body;
    }

    /**
     * 修改文章
     */
    public function edit()
    {
        if ( request()->isPost() ) {
            $param = input('post.');
            if ( !$this->archivesValidate->check($param) ) {
                return ['code' => -1, 'data' => '', 'msg' => $this->archivesValidate->getError()];
            }
            $param['body'] = $this->convertPicture($param['body']);
            $siteId        = $param['site_id'];
            unset($param['site_id']);
            $res = $this->archivesAPI->site($siteId)->archivesModify($param);

            return json($res);
        }

        $id     = input('archives_id');
        $siteId = input('site_id');
        $this->validateSite($siteId);
        $this->assign('siteId', $siteId);
        $data = $this->archivesAPI->site($siteId)->getArchivesOne($id);
        if ( empty($data) ) exit('获取结果失败');
        $host               = $this->archivesAPI->site($siteId)->getHost();
        $column             = \app\api\facade\Column::site($siteId)->getColumnOne($data['typeid']);
        $data['typeName']   = $column['typename'];
        $data['pubdate']    = date('Y-m-d H:i', $data['pubdate']);
        $data['litpicfull'] = $host . '/' . $data['litpic'];
        $data['flag']       = explode(',', $data['flag']);
        $data['body']       = $this->hostPicture($data['body'], $host);
        $this->assign(['data' => $data]);

        return view('edit');
    }

    /**
     * 删除文章
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
                return ['code' => -1, 'data' => '', 'msg' => $this->archivesValidate->getError()];
            $siteId = $site_id;
            $this->validateSite($siteId);

            $res = $this->archivesAPI->site($siteId)->archivesDel($id);

            return json($res);
        }

        $this->fetch();
    }

    /**
     * 栏目树
     */
    public function columnTree()
    {
        if ( !request()->isAjax() ) {
            $siteId = input('site_id');
            $this->validateSite($siteId);
            $this->assign('siteId', $siteId);

            return view('columnTree');
        }
        $siteId = input('site_id');
        $i      = input('get.id');
        ( $i == '#' ) && $i = 0;
        $res  = \app\api\facade\Column::site($siteId)->getColumnList(['typeId' => $i], 1);
        $rows = $res['rows'];
        $data = [];
        foreach ( $rows as $v ) {
            $data[] = [
                    'id'       => $v['id'],
                    'text'     => $v['typename'],
                    'children' => $v['lowerLevel']
            ];
        }

        return json($data);

    }
}
