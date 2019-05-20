<?php
$this->title = '紫罗兰花青素';
?>

<?php $this->beginBlock('content')?>

<div class="header">
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="content">
    <div class="flexslider">
        <ul class="slides">
            <?php foreach($model_ad as $vo){?>
                <li><img src="<?=$vo['image']?>" /></li>
            <?php }?>
        </ul>
    </div>


    <div class="main clearfix">
        <div class="index_product">
            <div class="index_title">产品展示</div>
            <div class="index_product_content">
                <ul>
                    <?php foreach ($model_goods as $vo) { ?>
                        <li>
                            <div class="product_img"><a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$vo['id']])?>"><img src="<?=\common\models\Goods::getCoverImg($vo['image'])?>"></a></div>
                            <div class="product_text">
                                <p class="product_name"><a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$vo['id']])?>"><?=$vo['name']?></a></p>
                                <p class="product_price">￥<?=$vo['linkSkuOne']['price']?>
                                    <a href="javascript:;"
                                       onclick="$.common.reqInfo(this)"
                                       data-conf="{url:'<?=\yii\helpers\Url::to(['mine/add-cart'])?>',data:{gid:<?=$vo['id']?$vo['id']:0?>,sku_id:<?=$vo['linkSkuOne']['id']?$vo['linkSkuOne']['id']:0?>}}"
                                    ><img src="<?=\Yii::getAlias('@assets')?>/images/car01.png"></a>
                                </p>
                            </div>
                        </li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <div class="index_advantage">
            <div class="index_title">公司简介</div>
            <div class="index_advantage_content">


                <p style="text-indent:2em; margin-bottom: 20px;"> 深圳市紫罗兰生物科技有限公司成立于2016年11月，自公司成立以来，一直致力于以科学技术为人类健康提供专业化的服务，是一家综合性高新技术企业。</p>
            </div>
        </div>

        <div class="index_video">
            <video controls="controls" width="100%" height="100%" poster="/assets/wechat/images/video.png">
                <source src="../video.mp4" type="video/mp4"></source>
            </video>
        </div>

        <div class="index_news">
            <div class="index_title">新闻资讯</div>
            <div class="index_news_content">
                <ul>
                    <?php foreach ($model_news as $vo){?>
                        <li>
                            <div class="news_left"><a href="<?=\yii\helpers\Url::to(['article/detail','id'=>$vo['id'],'menu_id'=>$vo['cid'],'con_type'=>$vo['linkNavPage']['type']])?>"><img src="<?=$vo['image']?>"></a></div>
                            <div class="news_right">
                                <a href="<?=\yii\helpers\Url::to(['article/detail','id'=>$vo['id'],'menu_id'=>$vo['cid'],'con_type'=>$vo['linkNavPage']['type']])?>">
                                    <h2><?=$vo['title']?></h2>
                                    <p class="news_time"><?=$vo['addtime']?date('Y-m-d',$vo['addtime']):''?></p>
                                    <p class="news_text"><?=$vo['desc']?></p>
                                </a>
                            </div>
                        </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </div>



</div>


<?=\frontend\widgets\Footer::widget(['current_action'=>'index'])?>

<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script src="<?=\Yii::getAlias('@assets')?>/js/jquery.flexslider-min.js"></script>
<script>
    $('.flexslider').flexslider({
        directionNav: true,
        pauseOnAction: false
    });
</script>

<?php $this->endBlock()?>
