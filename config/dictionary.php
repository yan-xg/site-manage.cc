<?php

/**
 * 公共字典
 */
return [
    // 主题颜色
        'theme_color' => [
                ['id' => 0, 'value' => '无',],
                ['id' => 1, 'value' => '红',],
                ['id' => 2, 'value' => '黄',],
                ['id' => 3, 'value' => '蓝',],
                ['id' => 4, 'value' => '绿',],
        ],
    // 站点类型
        'category'    => [1 => '品牌站', '综合站'],
        'site'        => [
                'host'              => '192.168.8.141', // 正式 8.211
                'port'              => '80',
                'create'            => [
                        'web_directory' => '', // 网站目录，为空情况下会自动创建新目录
                        'mysql_switch'  => 1, // 1 开启 0 为关闭
                        'mysql_detail'  => config('database.charset'),
                        'php_switch'    => 1, // PHP是否开启，默认开启。
                        'php_type'      => 'php56',
                        'code_url'      => 'http://file.vsokgo.com/control.zip',
//                        'domain_exist_check' => 1,// 如果有该参数，会将已经存在的网站给覆盖。
                ],
                'batch_upload_path' => '../uploads/site/',
                'create_status'     => ['未创建', '创建中', '创建成功', '创建失败'],
        ],
        'web'         => [
                'archives' => [
                        'flag' => [
                                'h' => '头条[h]', 'c' => '推荐[c]', 'f' => '幻灯[f]', 'a' => '特荐[a]',
                                's' => '滚动[s]', 'b' => '加粗[b]', 'p' => '图片[p]', 'j' => '跳转[j]',
                        ]
                ],
                'header'   => false, // 开启接口验证
        ]
];