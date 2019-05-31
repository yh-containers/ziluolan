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
            'appid'=>'******--',
            'appsecret'=>'*****',
            'class' => 'common\components\Wechat'
        ],
    ],
];
