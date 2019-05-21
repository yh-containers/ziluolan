<?php
$this->title = '个人中心';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<div class="content">
    <div class="Personal clearfix">
        <div class="pic clearfix">
            <div class="wrap">
                <div class="img"><img src="<?=$user_model['image']?>" /></div>
                <p><?=$user_model['username']?></p>
                <p>会员号:<?=$user_model['number']?></p>
            </div>
        </div>
        <div class="list clearfix">
            <ul>

                <a href="<?=\yii\helpers\Url::to(['info'])?>" >
                    <li class="clearfix">
                        <div class="left fl"><i class="i1"></i>个人资料</div>
                        <div class="right fr"></div>
                    </li>
                </a>


                <a href="/m/order.html"> <li class="clearfix">
                        <div class="left fl"><i class="i3"></i>我的订单</div>
                        <div class="right fr"></div>
                    </li></a>


                <a href="/m/warehouse.html?iid=1"><li class="clearfix">
                        <div class="left fl"><i class="i4"></i>我的仓库</div>
                        <div class="right fr"></div>
                    </li>
                </a>

                <!--<a href="/m/daishou.html"><li class="clearfix">
                  <div class="left fl"><i class="i5"></i>我的代售</div>
                  <div class="right fr"></div>
                </li>
                </a>-->

                <a href="/m/address.html"><li class="clearfix">
                        <div class="left fl"><i class="i6"></i>收货地址</div>
                        <div class="right fr"></div>
                    </li>
                </a>

                <a href="/m/withdraw.html"><li class="clearfix">
                        <div class="left fl"><i class="i7"></i>账户余额</div>
                        <div class="right fr"></div>
                    </li>
                </a>

                <li class="clearfix">
                    <div class="left fl"><i class="i9"></i>分享名片</div>
                    <div class="right fr"></div>
                </li>

                <a href="/m/referee.html">
                    <li class="clearfix">
                        <div class="left fl"><i class="i9"></i>推荐人</div>
                        <div class="right fr"></div>
                    </li>
                </a>
                <a href="/m/about.html">
                    <li class="clearfix">
                        <div class="left fl"><i class="i10"></i>关于紫罗兰</div>
                        <div class="right fr"></div>
                    </li>
                </a>

            </ul>
        </div>


        <div class="list list1 clearfix">
            <ul>
                <li class="clearfix">
                    <div class="left fl"><i class="i10"></i><a href="<?=\yii\helpers\Url::to(['index/logout'])?>">注销登录</a></div>

            </ul>
        </div>

    </div>

    <p style="text-align:center; "><a href="tel:400-8039068">客服电话：400-8039068</a></p>
</div>
<?=\frontend\widgets\Footer::widget(['current_action'=>'mine'])?>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/js/jquery.flexslider-min.js"></script>
<script>


</script>
<?php $this->endBlock()?>

