<?php

namespace app\api\facade;

use think\Facade;

class Cypher extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\api\Cypher';
    }
}