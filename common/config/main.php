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
            'appid'=>'wx7502c44234f7f6d8',
            'appsecret'=>'3195a284c8c57f9330969ef451ae371b',
            'class' => 'common\components\Wechat'
        ],
    ],
];
