<?php
$this->title = $title.($ch_title?'-'.$ch_title:'');
$this->params = [
        'meta_key' => $meta_key,
        'meta_desc' => $meta_desc,
];
?>

<?php $this->beginBlock('content')?>

<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>
<div class="clearfix" style="height:60px;"> </div>
<div class="main clearfix">
    <ul class="inside_nav">
        <?php foreach ($menu['linkNavPage'] as $vo){?>
            <li <?=$vo['id']==$id?'class="cur"':''?> ><a href="<?=\yii\helpers\Url::to(['','id'=>$vo['id']])?>"><?=$vo['name']?></a></li>
        <?php }?>
    </ul>
    <div class="about_content">
        <?=$content?>
    </div>
</div>


<?=\frontend\widgets\Footer::widget(['current_action'=>'index'])?>
<?php $this->endBlock()?>
