<div class="allnav_left">
    <div class="theclose"> <img src="/assets/wechat/images/close.png" /> </div>
    <ul>
        <li><a href="<?=\yii\helpers\Url::to(['index/index'])?>">首页</a> </li>
        <?php foreach ($list as $vo){?>
            <li> <a href="<?=\yii\helpers\Url::to($vo['url'])?>"><?=$vo['name']?></a> </li>
        <?php }?>
    </ul>
</div>
<div class="bk_gray"></div>