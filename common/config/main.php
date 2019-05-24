<?php
return [
    'name' => '紫罗兰花青素',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timeZone' => 'Asia/Shanghai',
    'layout'=>'layout',
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'wechat' => [
            'appid'=>'wx7678e59621135907--',
            'appsecret'=>'b750d9df8158449afc710d040179c8a4',
            'class' => 'common\components\Wechat'
        ],
    ],
];
