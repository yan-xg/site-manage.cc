<?php

namespace app\api\facade;

use think\Facade;

class Column extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\api\site\Column';
    }
}