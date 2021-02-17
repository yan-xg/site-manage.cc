<?php

namespace app\api\site;

use app\api\Http;
use tool\Log;

/**
 * 友情链接
 *
 * @package app\api\Flink
 */
class Flink extends APIBase
{
    protected $path = 'api/register.php';

    /**
     * 友情链接列表
     *
     * @param array $where  where 条件
     * @param int   $offset 页数
     * @param int   $limit  每页显示数量
     * @param array $order
     * @return array
     * @throws \app\api\HttpError
     */
    public function getFlinkList( array $where, int $offset, int $limit, array $order = [] ): array
    {
        $url  = $this->getUrl('getFlinkList');
        $data = [
                'page'  => $offset,
                'count' => $limit
        ];
        if ( !empty($order) ) {
            $data['isSort'] = true;
            $data['field']  = key($order);
            $data['order']  = current($order);
        }
        if ( !empty($where) ) {
            foreach ( $where as $k => $v ) $data[$k] = $v;
        }

        $res = Http::curl($url, $data, $this->header(), 'POST', true);

        if ( $res['status'] === 200 ) {
            return ['total' => $res['data']['total'], 'rows' => $res['data']['data']];
        }

        return ['total' => 0, 'rows' => []];
    }

    /**
     * 友情链接单条查询
     *
     * @param int $id
     * @return array
     */
    public function getFlinkOne( int $id ): array
    {
        $url    = $this->getUrl('getFlinkOne');
        $option = ['id' => $id];
        $res    = Http::curl($url, $option, $this->header(), 'GET');

        if ( $res['status'] === 200 ) return current($res['data']);

        return [];
    }

    /**
     * 友情链接单条新增、批量新增
     *
     * @param array $param
     * @return array
     */
    public function flinkAdd( array $param ): array
    {
        $url = $this->getUrl('flinkAdd');
        $res = Http::curl($url, $param, $this->header(), 'POST', true);
        if ( $res['status'] === 200 ) {
//            $lastId = $res['data']['lastId'];

            Log::write(sprintf('增加友情链接：%s(%s)', $param['webname'], 0));

            return modelReMsg(0, ['id' => 0], '创建成功');
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
    public function flinkModify( array $param ): array
    {
        $url = $this->getUrl('flinkModify');
        $res = Http::curl($url, $param, $this->header(), 'POST', true);
        if ( $res['status'] === 200 ) {
            Log::write(sprintf('编辑友情链接：%s(%s)', $param['webname'], $param['id']));

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
    public function flinkDel( int $id ): array
    {
        $url    = $this->getUrl('flinkDel');
        $option = ['ids' => $id];
        $res    = Http::curl($url, $option, $this->header(), 'POST', true);
        if ( $res['status'] === 200 ) {
            Log::write(sprintf('删除友情链接：%s', $id));

            return modelReMsg(0, '', '删除成功');
        }

        return modelReMsg(-1, '', '删除失败,请重新尝试!');
    }
}