<?php

namespace app\command;

use app\admin\model\Site;
use app\api\Http;
use app\api\MyCypher;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class uio extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('uio');
    }

    protected function execute( Input $input, Output $output )
    {
        $cypher = new MyCypher('0122035405060748', 'KDBUILDWEBSITE11');

        $php_encrypted = $cypher->encrypt('baidu.com|1611207805');

        $php_decrypted = $cypher->decrypt($php_encrypted);

        echo $php_encrypted;
        echo PHP_EOL;
        echo $php_decrypted;
        echo PHP_EOL;
        exit;
        $site = \app\admin\model\Site::where('site_id', 2000)->find();
        echo $site->name;
//        var_dump($site);;
        exit;
//        $res = Http::curl(
//                'http://192.168.9.10:9102/apitest/register.php/archivesAdd',
//                ['typeid' => 1, 'title' => '123123123', 'body' => 'body', 'litpic' => 'adskfjasldkf'],
//                0,
//                'POST',
//                true
//        );
//        var_export($res);
//        exit;
//
//
//        $res = Http::curl(
//                'http://192.168.9.10:9102/apitest/register.php/getArchivesOne',
//                ['id' => 2],
//                0,
//                'GET'
//        );
//        var_dump($res);
//        exit;
        $res = Http::curl(
                'http://192.168.9.10:9102/apitest/register.php/getArchivesList',
                ['page' => 1, 'count' => 10, 'typeId' => 3],
                0,
                'POST',
                true
        );
        print_r($res);
        echo PHP_EOL;
//         指令输出
//        $output->writeln('app\command\uio');
    }
}
