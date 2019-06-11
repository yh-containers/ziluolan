<?php
$this->title = $title.($ch_title?'-'.$ch_title:'');
$this->params = [
        'meta_key' => $meta_key,
        'meta_desc' => $meta_desc,
];
?>

<?php $this->beginBlock('content')?>

<div class="header">
    <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$menu['name']?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>
<div class="clearfix" style="height:60px;"> </div>
<div class="main clearfix">
    
   <?=$content?>
</div>

<?=\frontend\widgets\Footer::widget(['current_action'=>'index'])?>
<?php $this->endBlock()?>
