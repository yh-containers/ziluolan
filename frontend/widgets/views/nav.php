<div class="allnav_left">
    <div class="theclose"> <img src="<?=\Yii::getAlias('@assets')?>/images/close.png" /> </div>
    <ul>
        <li><a href="<?=\yii\helpers\Url::to(['index/index'])?>">首页</a> </li>
        <?php foreach ($list as $vo){?>
            <li> <a href="<?=!is_array($vo['url'])?$vo['url']:\yii\helpers\Url::to($vo['url'])?>"><?=$vo['name']?></a> </li>
        <?php }?>
    </ul>
</div>
<div class="bk_gray"></div>