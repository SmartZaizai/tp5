<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        $signature = input('get.signature');
        //$signature = '30f330b2c70b648bc3620f54f43e81a8a8a384f0';
        $timestamp = input('get.timestamp');
        $nonce = input('get.nonce');
        $echostr = input('get.echostr');




        //$token = 'smartzaizai';

        $token = config('app.mytoken');

        file_put_contents('d://ab.txt','signature: '.$signature.';timestamp: '.$timestamp.';nonce: '.$nonce.';echostr: .'.$echostr.';token: '.$token);


        $tmpArr = array($timestamp,$nonce,$token);

        sort($tmpArr,SORT_STRING);

        $sing = sha1(implode($tmpArr));

        echo $sing;
        dump($tmpArr);
        exit();



        return 'ThinkPHP WelCome Smart 2018';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
