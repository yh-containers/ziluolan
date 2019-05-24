<?php
$this->title = '我的仓库';
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

    <!-- 业务范围-->
    <div class="class-nav clearfix">
        <ul class="clearfix wrap" style="padding:0px;">
            <li class="left <?=empty($state)?'cur':''?> "><a href="<?\yii\helpers\Url::to([''])?>">代售中</a></li>
            <li class="left <?=$state==1?'cur':''?>"   ><a href="<?\yii\helpers\Url::to(['','state'=>1])?>">仓库</a></li>
            <li class="left <?=$state==2?'cur':''?>"><a href="<?\yii\helpers\Url::to(['','state'=>2])?>">售完</a></li>
            <li class="left <?=$state==3?'cur':''?>"><a href="<?\yii\helpers\Url::to(['','state'=>3])?>">已提货</a></li>
        </ul>
    </div>
    <?php if(empty($state)){ ?>
        <p style="text-align:center;margin-top:1%;margin-bottom:3%;">代售中的产品售完后,会从仓库自动补货进行代售</p>
    <?php }; ?>
    <div class="pick">
        <div class="wrap clearfix">
            <ul class="clearfix">
                <volist name="cangku" id="val">
                    <li class="wow fadeInDown animate clearfix">
                        <div class="img">
                            <div class="pic fl"><a href="/m/view/{$val.product_id}.html"><img class="tran" src="__PUBLIC__{$val.pro.image}"></a></div>
                            <div class="content fl">
                                <p class="title"><a href="/m/view/{$val.product_id}.html">{$val.pro.name}</a></p>
                                <p>订单号:{$val.order_sn}</p>
                                <p class="price">单价: ¥ {$val.pro.price}</p>

                                <!--<p>赠送数目:{$val.yl_num}</p>-->
                                <p class="quantity">数目：{$val.num}</p>
                                <!--<p>当前状态: <?php //if($val['state']==0){ echo '排队代售中'; }else if($val['state']==1){  echo '代售中';  }else if($val['state']==2){ echo '已售完'; }else if($val['state']==3){  echo '已申请提货'.'-'; if($val['tihuo_state']==0){  echo '待发货';  }else if($val['tihuo_state']==1){ echo '待确定收货';  }else if($val['tihuo_state']==2){  echo '已完成'; };   };  ?>
              </p>-->
                            </div>

                            <div class="right fr" style="width: 140px;">
                                <?php /*if(($val['state']==0) && (($val['ynum'] ==0) || ($val['th_sn'] ==''))){ */?><!--

                                    <a href="javascript:;" onclick="ontihuo({$val.id})" style="padding: 7px 19px;">提货</a>
                                <?php /*}else if($val['state']==1){ */?>
                                    <a href="javascript:;" onclick="onQuxiaoDaishou({$val.id})" style="background:#8a868a;padding: 7px 19px;">取消代售</a>
                                <?php /*}else if($val['state']==2){ */?>
                                    <a href="javascript:;" style="">代售完</a>
                                    <a href="javascript:;" onclick="oncangkudel({$val.id})" style="background:#9E9C9E;padding: 7px 19px;">删除记录</a>

                                --><?php /*}else{ */?>
                                    <a href="javascript:;" style="padding: 7px 19px;background:#898989">提货中</a>
                                <?php //} ?>
                            </div>
                        </div>
                    </li>
                </volist>
            </ul>
        </div>
    </div>
</div>

<?=\frontend\widgets\Footer::widget(['current_action'=>'mine'])?>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

</script>
<?php $this->endBlock()?>

