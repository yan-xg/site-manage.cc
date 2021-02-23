<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2021/1/13
 * Time: 11:18
 */
namespace app\admin\controller;

use app\common\lib\Upload;
use app\admin\model\Theme as ThemeModel;
use think\Model;
use think\Request;
use think\Validate;
use tool\Log;
use app\admin\model\Operate;


class Theme extends Base{
    /**
     * 显示主题列表
    **/
    public function index(){
        if(request()->isAjax()){

            $limit = input('param.limit');
            $temp_name = input('param.theme_name');
            $cat_id = input('param.cat_id');
            $where = [];

            if(!empty($temp_name))
                $where[] = ['t.temp_name' , 'like' , '%'.$temp_name . '%'];

            if(!empty($cat_id))
                $where[] = ['t.cat_id' , 'eq' , $cat_id];

            $themeModel = new ThemeModel();
            $list = $themeModel->getThemesList($limit,$where);

            if(0 == $list['code']) {
                $data = $list['data']->all();

                $themeColor = config('dictionary.theme_color');
                $themeColor = array_column($themeColor, 'value', 'id');
                foreach ( $data as &$v ) {
                    $v['color'] = changeString($v['color'], $themeColor);
                    $v['yulantu'] = json_decode($v['yulantu'] ,JSON_UNESCAPED_UNICODE);
                }

                return json(['code' => 0, 'msg' => 'ok', 'count' => $list['data']->total(), 'data' => $data]);
            }

            return json(['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []]);
        }

        return $this->fetch();
    }

    /**
     * 显示添加主题页并添加
    */
    public function add(){
        if (request()->isAjax()) {

            $param = input('post.');
            unset($param['file']);

            $rules = [
                'temp_name|主题名称' => 'require',
                'temp_src|PC端主题地址' => 'require',
            ];
            $validate = new Validate($rules);
            if (!$validate->check($param)) {
                return json(['code' => -1, 'msg' => $validate->getError()]);
            }

            /*
            if(!empty($param['temp_src']) && !file_exists($param['temp_src']))
                return json(['code' => -1, 'msg' => 'PC端主题地址不存在']);

            if(!empty($param['m_temp_src']) && !file_exists($param['m_temp_src']))
                return json(['code' => -1, 'msg' => '移动主题地址不存在']);
            */

            $param['yulantu'] = $this->formatYulantu($param['yulantu']);
            $param['color'] = !empty($param['color']) ? implode(',',$param['color']) : 0;
            $param['add_time'] = date('Y-m-d H:i:s',time());

            $themeModel = new ThemeModel();
            $res = $themeModel->addTheme($param);

            if($res['code']==0){
                Log::write("添加主题：" . $param['temp_name']);
            }
            return json($res);
        }

        $theme_color = config('dictionary.theme_color');
        $this->assign([
            'theme_color'=>$theme_color
        ]);
        return $this->fetch();
    }

    /**
     * 显示更新主题页并修改
    */
    public function edit(){
        if (request()->isAjax()) {

            $param = input('post.');
            unset($param['file']);

            $rules = [
                'temp_name|主题名称' => 'require',
                'temp_src|PC端主题地址' => 'require',
            ];
            $validate = new Validate($rules);
            if (!$validate->check($param)) {
                return json(['code' => -1, 'msg' => $validate->getError()]);
            }

            if(!empty($param['yulantu']))
                $param['yulantu'] = $this->formatYulantu($param['yulantu']);

            /*
            if(!empty($param['temp_src']) && !file_exists($param['temp_src']))
                  return json(['code' => -1, 'msg' => 'PC端主题地址不存在']);

            if(!empty($param['m_temp_src']) && !file_exists($param['m_temp_src']))
                return json(['code' => -1, 'msg' => '移动主题地址不存在']);
            */

            $param['color'] = !empty($param['color']) ? implode(',',$param['color']) : 0;
            $param['edit_time'] = date('Y-m-d H:i:s',time());

            $themeModel = new ThemeModel();
            $res = $themeModel->editTheme($param);

            if($res['code']==0){
                Log::write("编辑主题：" . $param['temp_name']);
            }
            return json($res);
        }

        $id = input('param.id');

        $themeModel = new ThemeModel();
        $info = $themeModel->getThemeOne($id)['data'];

        $info['color'] = $info['color'] ? explode(',',$info['color']) : ['0'];
        $info['yulantu'] = json_decode($info['yulantu'] ,JSON_UNESCAPED_UNICODE);
        $info['yulantu_num'] = (count($info['yulantu'])<1) ? 1 : count($info['yulantu']);

        $theme_color = config('dictionary.theme_color');
        $this->assign([
            'info'=>$info,
            'theme_color'=>$theme_color
        ]);

        return $this->fetch();
    }

    /**
     * 删除指定主题
    */
    public function del(){
        if (request()->isAjax()) {

            $id = input('param.id');
            $name = input('param.name');

            $themeModel = new ThemeModel();
            $res = $themeModel->delTheme($id);

            if($res['code']==0){
                Log::write("删除主题：" . $name);
            }
            return json($res);
        }
    }

    /**
     * 显示用户操作主题日志
    */
    public function log(){
        if (request()->isAjax()) {

            $limit = input('param.limit');
            $operateTime = input('param.operate_time');

            $where = [];
            $where[] = ['operate_method', 'like', "%theme%"];

            $user_name = session('admin_user_name');
            if($user_name!='' && $user_name!='admin'){ // 查找当前用户的操作日志
                $where[] = ['operator', 'eq', $user_name];
            }

            if (!empty($operateTime)) {
                $where[] = ['operate_time', 'between', [$operateTime, $operateTime. ' 23:59:59']];
            }

            $operateModel = new Operate();
            $list = $operateModel->getOperateLogList($limit, $where);

            if(0 == $list['code']) {

                return json(['code' => 0, 'msg' => 'ok', 'count' => $list['data']->total(), 'data' => $list['data']->all()]);
            }

            return json(['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []]);
        }

        return $this->fetch();
    }


    /**
     * 去除空预览图
     * @param array $yulantu
    */
    public function formatYulantu($yulantu){
        foreach ($yulantu as $key=>$val){
            if($val['thumb']=='' && $key>0){
                unset($yulantu[$key]);
            }
        }
        $yulantu = !empty($yulantu) ? json_encode(array_values($yulantu) ,JSON_UNESCAPED_UNICODE) : '';//重新定义下标并转为json
        return $yulantu;
    }

    /**
     * 图片上传
     * @param Request $request
    **/
    public function uploadImg(Request $request){
        if( $request ) {
            $path = 'images/';
            return (new Upload())->uploadOnePic($path);
        }
    }

    /**
     * 主题压缩包上传
     * @param Request $request
    **/
    public function uploadZip(Request $request){
        if ( $request ){
            $path = 'templets/';
            $flag = input('param.flag');
            $theme_id = input('param.theme_id');
            $path .= ($flag == '#m_temp_src') ? 'm/' : 'pc/';

            if( is_numeric($theme_id) && $theme_id != '' ) {
                $path .= $theme_id.'/';
            }else{
                $themeModel = new ThemeModel();
                $res = $themeModel->getMaxThemeId();
                if( $res['code'] == 0) {
                    $path .= ($res['data']+1).'/';
                }
            }

            return (new Upload())->uploadZip($path);
        }
    }

}