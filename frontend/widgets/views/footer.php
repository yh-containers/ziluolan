<!-- 底部-->
<!-- 底部-->
<div class="clearfix" style="height:100px;"> </div>
<div class="boom_kf">
    <a class="b_kf_index cur <?=$current_action=='index'?'active':''?>" href="<?=\yii\helpers\Url::to(['index/index'])?>"><i></i>
        <span>首页</span></a>
    <a class="b_kf_feek cur <?=$current_action=='cate'?'active':''?>"  href="<?=\yii\helpers\Url::to(['goods/cate'])?>"><i></i>
        <span>分类</span></a>
    <a class="b_kf_phone cur <?=$current_action=='cart'?'active':''?>" href="<?=\yii\helpers\Url::to(['mine/cart'])?>"><i></i>
        <span>购物车</span></a>
    <a class="b_kf_qq cur <?=$current_action=='mine'?'active':''?>" href="<?=\yii\helpers\Url::to(['mine/index'])?>"><i></i>
        <span>个人中心</span></a>
</div>