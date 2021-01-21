<?php

namespace app\command;

use app\api\facade\Column;
use app\api\site\Archives;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\api\facade\Cypher;

class uio extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('uio');
    }

    protected function execute( Input $input, Output $output )
    {
        $php_encrypted = Cypher::encrypt('baidu.com|1611207805');

        $php_decrypted = Cypher::decrypt($php_encrypted);

        echo $php_encrypted;
        echo PHP_EOL;
        echo $php_decrypted;
        echo PHP_EOL;
        exit;
//        $output->writeln('app\command\uio');
    }
}
