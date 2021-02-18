<?php

namespace app\api\facade;

use think\Facade;

class Site extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\api\site\Site';
    }
}
