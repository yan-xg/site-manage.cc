<?php

namespace app\admin\controller;

use app\admin\validate\SiteValidate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use think\App;
use app\admin\model\Site as SiteModel;
use app\admin\model\Theme as ThemeModel;
use app\api\facade\Site as SiteAPI;
use app\api\site\Common;
use think\Db;
use think\Request;
use app\common\lib\Upload;

class Site extends Base
{
    protected $siteModel;
    protected $siteValidate;
    protected $themeModel;
    protected $dateFormat = 'Y-m-d H:i';
    protected $isRewrite  = ['否', '是'];
    protected $isH5       = ['否', '是'];
    protected $status
                          = [
                    '<button class="layui-btn layui-btn-danger layui-btn-xs">禁用</button>',
                    '<button class="layui-btn layui-btn-success layui-btn-xs">启用</button>'
            ];
    protected $common;

    /**
     * Site constructor.
     *
     * @param App|null     $app
     * @param SiteModel    $siteModel
     * @param SiteValidate $siteValidate
     * @param ThemeModel   $themeModel
     */
    public function __construct(
            App $app = null,
            SiteModel $siteModel,
            SiteValidate $siteValidate,
            ThemeModel $themeModel,
            Common $common
    )
    {
        parent::__construct($app);
        $this->siteModel    = $siteModel;
        $this->siteValidate = $siteValidate;
        $this->themeModel   = $themeModel;
        $this->common       = $common;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        if ( request()->isAjax() ) {

            $limit  = input('param.limit');
            $search = input('param.search');
            $where  = [];
            if ( !empty($search) ) $where[] = ['name', 'like', $search . '%'];

            $list = $this->siteModel->lists($limit, $where);

            if ( 0 == $list['code'] ) {
                $data          = $list['data']->toArray();
                $total         = $data['total'];
                $rows          = $data['data'];
                $isRewrite     = $this->isRewrite;
                $themeId       = array_column($rows, 'temp_id');
                $themeName     = $this->themeModel->getKeyName($themeId);
                $create_status = config('dictionary.site.create_status');
                foreach ( $rows as &$v ) {
                    $v['rewrite']   = $isRewrite[$v['is_rewrite']];
                    $v['temp_name'] = $themeName[$v['temp_id']];
                    $v['creRes']    = $create_status[$v['create_status']];
                }
                unset($v);

                return json(['code' => 0, 'msg' => 'ok', 'count' => $total, 'data' => $rows]);
            }

            return json(['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []]);
        }

        return $this->fetch();
    }

    /**
     * 获取域名
     */
    public function domain()
    {
        $kw = input('param.q');
        if ( empty($kw) ) $kw = '';
        $data = SiteAPI::domain($kw);
        $data = array_combine($data, $data);

        return json(['code' => 0, 'msg' => '获取成功', 'data' => $data]);
    }

    /**
     * 获取IP
     */
    public function ip()
    {
        $kw = input('param.q');
        if ( empty($kw) ) $kw = '';
        $data = SiteAPI::ip($kw);
        $data = array_combine($data, $data);

        return json(['code' => 0, 'msg' => '获取成功', 'data' => $data]);
    }

    /**
     * 显示创建资源表单页.保存新建的资源
     *
     * @return []
     */
    public function create()
    {
        if ( request()->isPost() ) {

            $param = input('post.');
            unset($param['temp_name']);
            if ( !$this->siteValidate->check($param) ) {
                return ['code' => -1, 'data' => '', 'msg' => $this->siteValidate->getError()];
            }

            $res = $this->siteModel->add($param);

            return json($res);
        }

        $this->assign([
                'roles' => ( new \app\admin\model\Role() )->getAllRoles()['data'],
                'temp'  => $this->themeModel->getKeyName(),
        ]);

        return $this->fetch('add');
    }

    /**
     * 查询创建结果
     */
    public function createRes()
    {
        if ( request()->isAjax() ) {

            $id  = input('post.id');
            $res = $this->siteModel->createRes($id);

            return json($res);
        }
    }

    /**
     * 显示指定的资源
     *
     * @return \think\Response
     */
    public function read()
    {
        $id                = input('param.site_id');
        $data              = $this->siteModel->getSiteById($id)['data'];
        $data['add_time']  = date($this->dateFormat, strtotime($data['add_time']));
        $data['edit_time'] = date($this->dateFormat, strtotime($data['edit_time']));
        $data['rewrite']   = $this->isRewrite[$data['is_rewrite']];
        $data['theme']     = $this->themeModel->getKeyName($data['temp_id']);
        $data['status']    = $this->status[$data['status']];
        $create_status     = config('dictionary.site.create_status');
        $data['creRes']    = $create_status[$data['create_status']];
        $this->assign($data);

        return view('read');
    }

    /**
     * 显示编辑资源表单页并修改
     *
     * @return array|\think\response\Json|\think\response\View
     */
    public function edit()
    {
        if ( request()->isPost() ) {

            $param = input('post.');
            $rule  = [
                    'name'         => [
                            'require',
                            'unique' => [SiteModel::class, 'name', $param['site_id']]
                    ],
                    'web_domain'   => [
                            'require',
                            'unique' => [SiteModel::class, 'web_domain', $param['site_id']]
                    ],
                    'temp_id'      => 'require',
                    'is_rewrite'   => 'number',
                    'hj_site_id'   => 'number',
                    'hj_m_site_id' => 'number'
            ];
            if ( !$this->siteValidate->check($param, $rule) ) {
                return ['code' => -1, 'data' => '', 'msg' => $this->siteValidate->getError()];
            }
            $off_on                      = ['off' => 0, 'on' => 1];
            $param['domain_exist_check'] = $off_on[( $param['domain_exist_check'] ?? 'off' )];

            $res = $this->siteModel->edit($param);

            return json($res);
        }
        $id = input('param.site_id');

        $this->assign([
                'data'  => $this->siteModel->getSiteById($id)['data'],
                'roles' => ( new \app\admin\model\Role() )->getAllRoles()['data'],
                'temp'  => $this->themeModel->getKeyName()
        ]);

        return view('edit');
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return array|\think\Response
     */
    public function delete( $id )
    {
        if ( request()->isAjax() ) {
            if ( empty($id) ) return ['code' => -1, 'data' => '', 'msg' => '数据格式不正确'];

            $res = $this->siteModel->del($id);

            return json($res);
        }
    }

    /**
     * 选择主题
     */
    public function theme()
    {
        if ( request()->isAjax() ) {
            $limit    = input('param.limit');
            $search   = input('param.search');
            $color    = input('param.color');
            $category = input('param.category');
            $where    = [];
            if ( !empty($search) ) {
                $where[] = Db::raw(sprintf('t.temp_name regexp "%s"', $search));
            }
            if ( $color != '' ) {
                $where[] = Db::raw(sprintf('FIND_IN_SET(%s, t.color) > 0 ', $color));
            }
            if ( $category != '' ) {
                $where[] = ['t.cat_id', '=', $category];
            }

            $list = $this->themeModel->getThemesList($limit, $where);

            if ( 0 == $list['code'] ) {
                $data       = $list['data']->toArray();
                $total      = $data['total'];
                $rows       = $data['data'];
                $category   = config('dictionary.category');
                $themeColor = config('dictionary.theme_color');
                $themeColor = array_column($themeColor, 'value', 'id');
                $isH5       = $this->isH5;
                foreach ( $rows as &$v ) {
                    $v['category']  = $category[$v['cat_id']];
                    $v['color']     = changeString($v['color'], $themeColor);
                    $v['is_h5']     = $isH5[$v['is_h5']];
                    $v['temp_id']   = $v['theme_id'];
                    $v['edit_time'] = empty($v['edit_time']) ? '' : date('Y-m-d', strtotime($v['edit_time']));
                }
                unset($v);

                return json(['code' => 0, 'msg' => 'ok', 'count' => $total, 'data' => $rows]);
            }

            return json(['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []]);
        }

        return $this->fetch();
    }

    /**
     * 更新页面
     **/
    public function updateWeb()
    {
        if ( request()->isAjax() ) {
            $type = input('param.type');
            $data = input('param.data');
            if ( empty($data) )
                return json(['code' => -1, 'msg' => '站点为空，请选择站点！']);

            switch ( $type ) {
                case 'updateIndex':
                    $up = $this->common->updateIndex($data);
                    break;
                case 'updateColumn':
                    $up = $this->common->updateColumn($data);
                    break;
                case 'updateArticle':
                    $up = $this->common->updateArticle($data);
                    break;
                default:
                    $up = json_encode(['code' => -1, 'msg' => '没有该操作'], true);
                    break;
            }

            return json_decode($up, true);
        }
    }

    /**
     * 批量上传
     *
     * @return \think\response\Json|\think\response\View
     */
    public function batchCreate()
    {
        if ( !empty($_FILES) ) {
            $file = request()->file('file');
            $path = config('dictionary.site.batch_upload_path');
            $info = $file->move($path);
            if ( $info ) {

                $res = $this->batchValidate($path . '/' . $info->getSaveName());

                return json(['code' => $res['code'], 'message' => $res['message'], 'data' => ['data' => $res['data'], 'filename' => $info->getSaveName()]]);
            } else {
                // 上传失败获取错误信息
                return json(['code' => -1, 'message' => $file->getError()]);
            }
        }

        return view('batchCreate');
    }

    /**
     * 执行批量新增动作
     */
    public function batchexec()
    {
        $filename = input('post.filename');
        $path     = config('dictionary.site.batch_upload_path');
        $res      = $this->batchValidate($path . '/' . $filename);
        if ( $res['code'] != 0 ) {
            return json(['code' => $res['code'], 'message' => $res['message'], 'data' => ['data' => $res['data'], 'filename' => $filename]]);
        }
        $data = $res['data'];

        $error = [];
        foreach ( $data as $v ) {
            $res = $this->siteModel->add(['name' => $v[0], 'temp_id' => $v[1], 'web_domain' => $v[2], 'm_domain' => $v[3], 'ip' => $v[4]]);
            if ( $res['code'] != 0 ) {
                $error[] = ['name' => $v[0], 'error' => $res['msg']];
            }
        }

        if ( empty($error) ) {
            return json(['code' => 0, 'data' => '', 'message' => '执行成功']);
        }

        return json(['code' => -1, 'data' => $error, 'message' => '执行失败']);
    }

    /**
     * 下载批量生成模版文件
     *
     * @return \think\response\Download
     */
    public function download()
    {
        header('Expires: 0');
        header('Content-Encoding: utf-8');
        header('Content-type: text/csv; charset=utf-8');
        header('location:' . \think\facade\Request::domain() . '/site.xlsx');
//        $path     = config('dictionary.site.batch_upload_path');
//        $download = new \think\response\Download($path . '/site.csv');

//        return $download->name('site.csv');
    }

    /**
     * @param $filePath
     */
    private function batchValidate( $filePath )
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $data        = [];
        foreach ( $sheet->getRowIterator(2) as $row ) {
            $tmp = [];
            foreach ( $row->getCellIterator() as $cell ) {
                $tmp[] = $cell->getFormattedValue();
            }
            if ( !empty(array_filter($tmp)) )
                $data[$row->getRowIndex()] = $tmp;
        }
        // 验证数据本身是否有重复
        // 验证域名是否重复

        // 站点名称验证
        $webName = $this->siteModel->where('name', 'in', array_column($data, 0))->field(['name'])->select()->toArray();
        if ( !empty($webName) ) {

            $webNameArr = array_column($webName, 'name');

            return ['code' => -1, 'message' => '名称在数据库中有存在', 'data' => $webNameArr];
        }

        // 验证域名是否重复
        $webDomain = $this->siteModel->where('web_domain', 'in', array_column($data, 2))->field(['web_domain'])->select()->toArray();
        if ( !empty($webDomain) ) {

            $webDomainArr = array_column($webDomain, 'web_domain');

            return ['code' => -2, 'message' => '站点域名在数据中存在', 'data' => $webDomainArr];
        }

        // 站点模版ID存在。
        $themeId  = array_column($data, 1);
        $webTheme = $this->themeModel->where('theme_id', 'in', $themeId)->field(['theme_id'])->select()->toArray();
        $themeId2 = array_column($webTheme, 'theme_id');
        $result   = array_diff($themeId, $themeId2);
        if ( !empty($result) ) {
            return ['code' => -3, 'message' => '模版ID在数据库中不存在', 'data' => $result];
        }

        // 站点移动端重复
        $mDomain = $this->siteModel->where('m_domain', 'in', array_column($data, 3))->field(['m_domain'])->select()->toArray();
        if ( !empty($mDomain) ) {

            $mDomainArr = array_column($mDomain, 'm_domain');

            return ['code' => -4, 'message' => '移动站点域名在数据中存在', 'data' => $mDomainArr];
        }


        return ['code' => 0, 'message' => '验证成功', 'data' => $data];
    }

    /**
     * 上传站点文件
     *
     * @param Request $request
     */
    public function uploadControl( Request $request )
    {

        return ( new Upload() )->uploadZip(null, 'file', true);
    }
}
