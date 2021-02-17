<?php

namespace app\api\facade;

use think\Facade;

class Common extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\api\site\Common';
    }
}