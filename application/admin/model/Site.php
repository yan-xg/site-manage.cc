<?php

namespace app\admin\model;

use think\Model;
use tool\Log;

class Site extends Model
{
    protected $table = 'bsa_site';
    protected $pk    = 'site_id';

    /**
     * 获取数据
     *
     * @param $limit
     * @param $where
     * @return array
     */
    public function lists( $limit, $where )
    {
        try {
            $res = $this->field('*')->where($where)->order('site_id', 'desc')->paginate($limit);

        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }


    /**
     * 增加数据
     *
     * @param array $data
     * @return array
     */
    public function add( array $data )
    {
        try {
            $data['add_time'] = date('Y-m-d H:i:s');
            $this->insert($data);
            \app\api\facade\Site::site(1)->create();
            Log::write(sprintf("添加站点：%s(%s)", $data['name'], $this->getLastInsID()));
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '添加站点成功');
    }

    /**
     * 修改数据
     *
     * @param array $data
     * @return array
     */
    public function edit( array $data )
    {
        try {
            $data['edit_time'] = date('Y-m-d H:i:s');
            $this->save($data, ['site_id', $data['site_id']]);
            Log::write(sprintf('编辑站点：%s(%s)', $data['name'], $data['site_id']));
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '修改站点成功');
    }

    /**
     * 删除
     *
     * @param $id
     * @return array
     */
    public function del( $id )
    {
        try {
            $data = $this->where('site_id', $id)->find()->toArray();
            $this->where('site_id', $id)->delete();
            Log::write(sprintf('删除站点：%s(%s)', $data['name'], $id));
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '删除成功');
    }

    /**
     * 获取单条数据信息
     *
     * @param int $id
     * @return array
     */
    public function getSiteById( $id )
    {
        try {
            $theme = new Theme();
            $info  = $this->alias('s')->join([$theme->getTable() => 't'], 's.temp_id = t.theme_id')->where('s.site_id', $id)->field(['s.*', 't.temp_name'])->findOrEmpty()->toArray();
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }
}
