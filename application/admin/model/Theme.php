<?php
namespace app\admin\model;

use think\Model;

class Theme extends Model
{
    protected $table = 'bsa_theme';

    /**
     * 获取主题列表
     * @param $limit
     * @param $where
     * @return array
    */
    public function getThemesList( $limit, $where )
    {
        try {

            $res = $this->alias('t')
                ->join('bsa_site s','t.theme_id=s.temp_id','LEFT')
                ->field(['t.*,count(s.temp_id) as num'])
                ->group('t.theme_id')
                ->where($where)
                ->order('t.theme_id', 'desc')
                ->paginate($limit);

        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 获取一条主题信息
     * @param $id
     * @return array
     */
    public function getThemeOne( $id )
    {
        try {
            $info = $this->alias('t')
                    ->join('bsa_site s', 't.theme_id=s.temp_id', 'LEFT')
                    ->field(['t.*,count(s.temp_id) as num'])
                    ->group('t.theme_id')
                    ->where('theme_id', $id)
                    ->findOrEmpty()
                    ->toArray();
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }

    /**
     * 添加主题数据
     * @param array $param
     * @return array
     */
    public function addTheme( $param )
    {
        try {

            $has = $this->where('temp_name', $param['temp_name'])->find();
            if ( !empty($has) ) {

                return modelReMsg(-2, '', '主题名称已经存在');
            }

            $this->insert($param);
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '添加主题成功');
    }

    /**
     * 修改主题数据
     * @param array $param
     * @return array
     */
    public function editTheme( $param )
    {
        try {

            $has = $this->where('temp_name', $param['temp_name'])
                    ->where('theme_id', '<>', $param['theme_id'])
                    ->findOrEmpty()->toArray();
            if ( !empty($has) ) {

                return modelReMsg(-2, '', '主题名称已经存在');
            }

            $this->save($param, ['theme_id' => $param['theme_id']]);

        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '编辑主题成功');
    }

    /**
     * 删除主题
     * @param $id
     * @return array
     */
    public function delTheme( $id )
    {
        try {
            $has = $this->where('theme_id', $id)->find();
            if ( !$has ) {
                return modelReMsg(-2, '', '主题不存在');
            }

            $this->where('theme_id', $id)->delete();
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '删除成功');
    }

    /**
     * 获取ID=>name格式数组
     *
     * @param int|array|null $themeId
     * @return bool|array
     */
    public function getKeyName( $themeId = null )
    {
        $query = $this;
        if ( is_array($themeId) ) {
            $query = $query->where('theme_id', 'in', implode(',', $themeId));
        }

        if ( is_int($themeId) ) {
            $query = $query->where('theme_id', $themeId);
        }
        $rows = $query->field(['theme_id', 'temp_name'])
                ->select()
                ->toArray();
        $data = [];
        foreach ( $rows as $v ) $data[$v['theme_id']] = $v['temp_name'];
        if ( empty($data) ) return false;

        if ( is_int($themeId) ) return current($data);

        return $data;
    }

    /**
     * 获取最大的主题ID
    */
    public function getMaxThemeId(){
        try {
            $info = $this->max('theme_id');
        } catch ( \Exception $e ) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }
}