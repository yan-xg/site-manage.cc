<?php

namespace app\command;

use app\api\facade\Column;
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

    public function curl_post( $url, $data = [] )
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // POST数据

        curl_setopt($ch, CURLOPT_POST, 1);

        // 把post的变量加上

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;

    }

    protected function execute( Input $input, Output $output )
    {
        echo dirname(__FILE__);
        exit;
        echo Column::getSignature();
        exit;


        exit;
        $php_encrypted = Cypher::encrypt('6830b3f8b3f7f02e64d4239003bb18fe1');

        $res = $this->curl_post('http://www.snake.local/SiteRes', ['status' => 1, 'siteId' => 1, 'result' => json_encode(['a' => 1]), 'tocken' => $php_encrypted]);

        echo $res . PHP_EOL;

        exit;

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
