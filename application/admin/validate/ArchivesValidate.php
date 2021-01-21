<?php

namespace app\admin\validate;

use think\Validate;

class ArchivesValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule
            = [
                    'title'  => 'require',
                    'typeid' => 'require',
                    'body'   => 'require',
                    'litpic' => 'require',
            ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message
            = [
                    'title.require'  => '文章标题不能为空',
                    'typeid.require' => '档案主类别不能为空',
                    'body.require'   => '文章内容不能为空',
                    'litpic.require' => '缩略图不能为空',
            ];
}