<?php

namespace app\api\site;

use app\api\Http;
use tool\Log;

/**
 * 文章内容
 *
 * @package app\api\archives
 */
class Archives extends APIBase
{
    protected $path = 'api/register.php';

    /**
     * 文章列表
     *
     * @param array $where  where 条件
     * @param int   $offset 页数
     * @param int   $limit  每页显示数量
     * @param array $order
     * @return array
     * @throws \app\api\HttpError
     */
    public function getArchivesList( array $where, int $offset, int $limit, array $order = [] ): array
    {
        $url  = $this->getUrl('getArchivesList');
        $data = [
                'page'  => $offset,
                'count' => $limit
        ];
        if ( !empty($order) ) {
            $data['isSort'] = false;
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
     * 文章单条查询
     *
     * @param int $id
     * @return array
     */
    public function getArchivesOne( int $id ): array
    {
        $url    = $this->getUrl('getArchivesOne');
        $option = ['id' => $id];
        $res    = Http::curl($url, $option, $this->header(), 'GET');

        if ( $res['status'] === 200 ) return current($res['data']);

        return [];
    }

    /**
     * 文章单条新增、批量新增
     *
     * @param array $param
     * @return array
     */
    public function archivesAdd( array $param ): array
    {
        $url = $this->getUrl('archivesAdd');
        $res = Http::curl($url, $param, $this->header(), 'POST', true);

        if ( $res['status'] === 200 ) {
            $lastId = $res['data']['lastId'];

            Log::write(sprintf('增加文章：%s(%s)', $param['title'], $lastId));

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
    public function archivesModify( array $param ): array
    {
        $url = $this->getUrl('archivesModify');
        $res = Http::curl($url, $param, $this->header(), 'POST', true);
        if ( $res['status'] === 200 ) {
            Log::write(sprintf('编辑文章：%s(%s)', $param['title'], $param['id']));

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
    public function archivesDel( int $id ): array
    {
        $url    = $this->getUrl('archivesDel');
        $option = ['ids' => $id];
        $res    = Http::curl($url, $option, $this->header(), 'POST', true);
        if ( $res['status'] === 200 ) {
            Log::write(sprintf('删除文章：%s', $id));

            return modelReMsg(0, '', '删除成功');
        }

        return modelReMsg(-1, '', '删除失败,请重新尝试!');
    }
}