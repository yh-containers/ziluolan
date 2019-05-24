<?php
$this->title = '分享二维码';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>
<style>
    .share_warp {width: 100%;height: 100%;position: fixed;top: 60px;left: 0px;background:url(<?=\Yii::getAlias('@assets')?>/images/share_bg.jpg) no-repeat;background-size:cover;background-position:center bottom;}
    .share_warp .qrcode {width: 55%;margin: 20% auto 15px;}
    .share_warp .text {width: 100%;text-align: center;}
    .share_warp .text p {color: #fff;font-size: 0.95rem;line-height: 1.6}
    .share_warp .btn {display: block;width: 120px;height: 38px;line-height: 38px;text-align: center;margin: 15px auto 0;background:#240A33;color: #fff;}
    .share_warp .bottom {width: 100%;position: absolute;bottom: 120px;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<div class="content">
    <div class="share_warp info clearfix">

            <div class="share_con">
                <div class="qrcode"><img src="<?=$wechat_qrcode_img?>" alt="" /></div>
                <div class="text">
                    <p>按住图片二维码，点击识别图中二维码</p>
                    <p>关注紫罗兰公众号，成为<span style="color: blue;"><?=$user_model['username']?></span>的消费股东</p>
                    <p><?=$user_model['username']?>的会员号是：<?=$user_model['number']?></p>
                </div>
            </div>
        <div class="bottom"><img src="<?=\Yii::getAlias('@assets')?>/images/share_bottom.png" alt="" /></div>
    </div>
</div>



<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>


</script>
<?php $this->endBlock()?>

