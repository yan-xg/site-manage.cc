<?php

namespace app\admin\validate;

use think\Validate;

class FlinkValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule
            = [
                    'sortrank' => 'require',
                    'url'      => 'require',
                    'webname'  => 'require',
            ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message
            = [
                    'sortrank.require' => '排序不能为空',
                    'url.require'      => '链接地址不能为空',
                    'webname.require'  => '网站名称不能为空',
            ];
}