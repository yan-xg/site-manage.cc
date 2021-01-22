<?php

namespace app\admin\validate;

use app\admin\model\Site;
use think\Container;
use think\Db;
use think\exception\ClassNotFoundException;
use think\Validate;

class SiteValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule
            = [
                    'name'         => [
                            'require',
                            'unique' => [Site::class,]
                    ],
                    'web_domain'   => [
                            'require',
                            'unique' => [Site::class,]
                    ],
                    'temp_id'      => 'require',
                    'is_rewrite'   => 'number',
                    'hj_site_id'   => 'number',
                    'hj_m_site_id' => 'number',
                    'ip'           => 'require'
            ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message
            = [
                    'name.require'        => '站点名称不能为空',
                    'name.unique'         => '站点名称已存在',
                    'web_domain.require'  => '站点域名信息不能为空',
                    'web_domain.unique'   => '站点域名已存在',
                    'temp_id.require'     => '域名地址不能为空',
                    'is_rewrite.number'   => '是否伪静态字段为数字',
                    'hj_site_id.number'   => '汇聚通PC端站点ID必须是数字',
                    'hj_m_site_id.number' => '汇聚通移动端端站点ID必须是数字',
                    'ip.require'          => 'IP地址不能为空',
            ];
}