<?php

namespace app\index\controller;

use think\Controller;
use think\facade\Request;

class Index extends Controller
{
    public function index()
    {
        $this->redirect('/admin');
    }

    public function hello( $name = 'ThinkPHP5' )
    {
        return 'hello,' . $name;
    }
}
