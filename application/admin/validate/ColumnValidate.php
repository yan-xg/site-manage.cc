<?php

namespace app\admin\validate;

use think\Validate;

class ColumnValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule
            = [
                    'sortrank'    => 'require|number',
                    'typedir'     => 'require',
                    'templist'    => 'require',
                    'seotitle'    => 'require',
                    'keywords'    => 'require',
                    'description' => 'require',
//                    'typename'    => 'require',
            ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message
            = [
                    'sortrank.require'    => '排列顺序不能为空',
                    'sortrank.number'     => '排列顺序必须是数字',
                    'typedir.require'     => '文件保存路径不能为空',
                    'templist.require'    => '模版不能为空',
                    'seotitle.requre'     => 'seo标题不能为空',
                    'keywords.require'    => '关键字不能为空',
                    'description.require' => '描述不能为空',
//                    'typename.require'    => '名称不能为空',
            ];
}