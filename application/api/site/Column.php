<?php

namespace app\api\site;

use app\api\Http;
use tool\Log;

/**
 * 栏目内容
 *
 * @package app\api\column
 */
class Column extends APIBase
{
    protected $host = '192.168.9.10';

    protected $port = '9102';

    protected $path = 'api/register.php';

    /**
     * 获取栏目列表
     *
     * @param array $where  where 条件
     * @param int   $offset 页数
     * @param int   $limit  每页显示数量
     * @param array $order
     * @return array
     * @throws \app\api\HttpError
     */
    public function getColumnList( array $where, int $offset, int $limit, array $order = [] ): array
    {
        $url           = $this->getUrl('getColumnList');
        $data          = [];
        $data['page']  = $offset;
        $data['count'] = $limit;
        if ( !empty($order) ) {
            $data['isSort'] = true;
            $data['field']  = key($order);
            $data['order']  = current($order);
        }
        if ( !empty($where) ) {
            foreach ( $where as $k => $v ) $data[$k] = $v;
        }

        $res = Http::curl($url, $data, 0, 'POST', true);

        if ( $res['status'] === 200 ) {
            return ['total' => $res['data']['total'], 'rows' => $res['data']['data']];
        }

        return ['total' => 0, 'rows' => []];
    }

    /**
     * 栏目单条查询
     *
     * @param int $id
     * @return array
     */
    public function getColumnOne( int $id ): array
    {
        $url    = $this->getUrl('getColumnOne');
        $option = ['id' => $id];
        $res    = Http::curl($url, $option, 0, 'GET');

        if ( $res['status'] === 200 ) return current($res['data']);

        return [];
    }

    /**
     * 栏目单条新增、批量新增
     *
     * @param array $param
     * @return array
     */
    public function columnAdd( array $param ): array
    {
        $url = $this->getUrl('columnAdd');
        $res = Http::curl($url, $param, 0, 'POST', true);

        if ( $res['status'] === 200 ) {
            $lastId = $res['data']['lastId'];
            Log::write(sprintf('增加栏目：%s(%s)', $param['typename'], $lastId));

            return modelReMsg(0, ['id' => $lastId], '创建成功');
        }

        return modelReMsg(-1, '', '添加失败,请重新尝试！');
    }

    /**
     * 修改
     *
     * @param array $param
     * @return array
     * @throws \app\api\HttpError
     */
    public function columnModify( array $param ): array
    {
        $url = $this->getUrl('columnModify');
        $res = Http::curl($url, $param, 0, 'POST', true);
        if ( $res['status'] === 200 ) {
            Log::write(sprintf('编辑栏目：%s(%s)', $param['typename'], $param['id']));

            return modelReMsg(0, '', '修改成功');
        }

        return modelReMsg(-1, '', '修改失败,请重新尝试!');
    }

    /**
     * 删除单条数据
     *
     * @param int $id
     * @return array
     * @throws \app\api\HttpError
     */
    public function columnDel( int $id ): array
    {
        $url    = $this->getUrl('columnDel');
        $option = ['ids' => $id];
        $res    = Http::curl($url, $option, 0, 'POST', true);
        if ( $res['status'] === 200 ) {
            Log::write(sprintf('编辑栏目：%s', $id));

            return modelReMsg(0, '', '删除成功');
        }

        return modelReMsg(-1, '', '删除失败,请重新尝试!');
    }
}