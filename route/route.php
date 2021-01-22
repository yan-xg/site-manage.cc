<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

//Route::get('hello/:name', 'index/hello');

Route::get('/admin/log/system', 'Qsnh\Think\Log\Controllers\LogViewerController@index');

// 结果获取
Route::post('/siteRes', 'app\common\controller\SiteRes@index');

//Route::group('/admin/site', function () {
//    Route::get('/', 'site@index');
//}, ['app\admin\controller']);

return [

];
