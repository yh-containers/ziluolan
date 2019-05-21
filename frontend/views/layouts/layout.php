<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name= "format-detection" content="telephone = no" />
    <title><?= empty($this->title)?\Yii::$app->name:$this->title?></title>
    <meta name="keywords" content="<?=empty($this->params['meta_key'])?'':$this->params['meta_key']?>" />
    <meta name="description" content="<?=empty($this->params['meta_desc'])?'':$this->params['meta_desc']?>" />
    <link rel="stylesheet" type="text/css" href="/assets/wechat/css/style.css" />
    <script type="text/javascript" src="/assets/wechat/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/assets/wechat/js/common.js"></script>
    <link rel="stylesheet" href="/assets/layui-v2.4.5/css/layui.css">
    <script type="text/javascript" src="/assets/layui-v2.4.5/layui.js"></script>
</head>
<?php if (isset($this->blocks['style'])): ?>
    <?= $this->blocks['style'] ?>

<?php endif; ?>
<body>
<!--左侧导航-->
<?=\frontend\widgets\Nav::widget()?>

<?php if (isset($this->blocks['content'])): ?>
        <?= $this->blocks['content'] ?>
<?php endif; ?>



</body>
</html>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/js/handle.js"></script>
<?php if (isset($this->blocks['script'])): ?>
    <?= $this->blocks['script'] ?>

<?php endif; ?>