<?php

namespace app\admin\controller;

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

        $res = $this->archivesAPI->site($siteId)->getArchivesList(['typeId' => $typeid], $page, $limit);

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
     * 将内容中的图片base64增加
     *
     * @param string $body
     * @return string
     */
    private function convertPicture( $body )
    {
        $reg  = "/(<img src=[\"|'])(.*?)([\"|'].*?>)/";
        $body = preg_replace($reg, '$1\$#!#\$$2\$#!#\$$3', $body);
        preg_match_all('/(\$\#\!\#\$)(.*?)(\$\#\!\#\$)/', $body, $matches);
        foreach ( $matches[2] as $k => $v ) {
            if ( strlen($v) < 300 ) {
                $body = str_replace(sprintf('%s%s%s', $matches[1][$k], $matches[2][$k], $matches[3][$k]), $matches[2][$k], $body);
            } else {
                $body = str_replace(
                        sprintf(
                                '%s%s%s',
                                $matches[1][$k],
                                str_replace([PHP_EOL, '\r'], '', $matches[2][$k]),
                                $matches[3][$k]
                        ),
                        $matches[2][$k],
                        $body
                );
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
        $data               = $this->archivesAPI->site($siteId)->getArchivesOne($id);
        $data['pubdate']    = date('Y-m-d H:i', $data['pubdate']);
        $data['litpicfull'] = 'http://192.168.9.10:9102/' . $data['litpic'];
        $data['flag']       = explode(',', $data['flag']);
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
        $res  = \app\api\facade\Column::site($siteId)->getColumnList(['typeId' => $i], 1, 1000);
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
