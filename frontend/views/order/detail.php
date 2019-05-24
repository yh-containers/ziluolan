<?php
$this->title = '订单详情';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>


<?php $this->endBlock()?>

<?php $this->beginBlock('content')?>


<div class="header">
    <a href="<?=\yii\helpers\Url::to(['order/index'])?>" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>

<div class="content">
    <!-- 业务范围-->
    <div class="module" >
        <div class="seller-state  wrap">
            <div class="state-cont">
                <?=empty($model) ? '' : $model->getStepFlowInfo($model['step_flow'])?>
            </div>
        </div>
    </div>
    <div class="address1 clearfix bj1">
        <div class="list clearfix  wrap">
            <ul>
                <?php if($model['rec_mode']==1){ ?>
                    <li class="clearfix">
                        <div class="left">
                            <p>收货地址</p>
                            <p><span><?=$model['linkAddr']['username']?>  </span><span><?=$model['linkAddr']['phone']?></span></p>
                            <p><?=$model['linkAddr']['addr'].' '.$model['linkAddr']['addr_extra']?> </p>
                        </div>
                    </li>
                <?php }else{ ?>
                    <li class="clearfix">
                        <div class="left">
                            <p>自提收货</p>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="pick">
        <div class="wrap clearfix">
            <ul class="clearfix">
                <?php if(!empty($model['linkGoods'])) foreach($model['linkGoods'] as $vo) {?>
                    <li class="wow fadeInDown animate clearfix">
                        <div class="img">
                            <div class="pic fl"><a href="javascript:;"><img class="tran" src="<?=$vo['img']?>"></a></div>
                            <div class="content fl">
                                <p class="title"><a href="javascript:;"><?=$vo['name']?></a></p>
                                <p class="title"><?=$vo['sku_name']?></p>
                                <p class="price">¥ <?=$vo['price']?> X <?=$vo['num']?> </p>
                            </div>
                        </div>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div class="bj1 orderinfo" id="orderinfo12">
        <div class=" wrap">
            <p>订单编号:<?=$model['no']?></p>
            <p>创建时间:<?=$model['create_time']?date('Y-m-d H:i:s',$model['create_time']):''?></p>

            <?php if(!empty($model['linkLogistics'])){?>
                <!--物流-->
                <p>快递名称:<?=$model['linkLogistics']['company']?></p>
                <p>快递单号:<?=$model['linkLogistics']['no']?></p>
            <?php }?>

            <p>发票类型:<?=empty($model)?'':\common\models\Order::getPropInfo('fields_invoice',$model['invoice_type'],'name')?></p>
            <?php
                $invoice_data = empty($model['invoice_content'])?null:json_decode($model['invoice_content'],true);
                if(!empty($invoice_data) && is_array($invoice_data))
                    foreach ($invoice_data as $vo){
                        if(isset($vo['name']) && isset($vo['value'])){
            ?>
                <p><?=$vo['name']?>:<?=$vo['value']?></p>
            <?php } } ?>
            <p>订单金额<?=$model['rec_mode']?'(含运费)':''?>:<span style="color: red">￥<?= $model['money'] ?> </span></p>
            <p>留言需求:<?=$model['remark']?></p>

        </div>
    </div>
    <!-- 底部-->
    <!-- 底部-->

    <div class="clearfix" style="height:100px;"> </div>
    <div class="footer-sub-order pro-footer clearfix" style="z-index:1">
        <?php if(in_array(\common\models\Order::U_ORDER_HANDLE_PAY,$handle)){?>
            <div class="fr tjdd"><a href="<?=\yii\helpers\Url::to(['pay','id'=>$model['id']])?>" style="color:#fff;">立即付款</a></div>
        <?php }?>
        <?php if(in_array(\common\models\Order::U_ORDER_HANDLE_SURE_REC,$handle)){?>
            <div class="fr tjdd">
                <a href="javascript:;" style="background:#FF7E00;color:#fff;"
                   onclick="$.common.reqInfo({url:'<?=\yii\helpers\Url::to(['receive'])?>',data:{id:'<?=$model['id']?>'},success:refresh_page},{confirm_title:'是否确定收货'})"
                >确定收货</a>
            </div>
        <?php }?>


        <?php if(in_array(\common\models\Order::U_ORDER_HANDLE_DEL,$handle)){?>
            <div class="fr tjdd">
                <a href="javascript:;" style="color:#fff;"
                   onclick="$.common.reqInfo({url:'<?=\yii\helpers\Url::to(['del'])?>',data:{id:'<?=$model['id']?>'},success:redirect_page},{confirm_title:'确定删除订单?'})"
                >删除订单</a>
            </div>
        <?php }?>

        <?php if(in_array(\common\models\Order::U_ORDER_HANDLE_CANCEL,$handle)){?>
            <div class="fr tjdd">
                <a href="javascript:;" style="color:#fff;"
                   onclick="$.common.reqInfo({url:'<?=\yii\helpers\Url::to(['cancel'])?>',data:{id:'<?=$model['id']?>'},success:refresh_page},{confirm_title:'确定取消订单?'})"
                >取消订单</a>
            </div>
        <?php }?>

    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

    $(function(){


    })
    //刷新页面
    function refresh_page(res){
        layui.layer.msg(res.msg)
        if(res.code===1){
            setTimeout(function(){location.reload()},1000)
        }
    }

    //跳转页面
    function redirect_page(res) {
        layui.layer.msg(res.msg)
        if(res.code===1){
            setTimeout(function(){window.location.href="<?=\yii\helpers\Url::to(['mine/index'])?>"},1000)
        }
    }

</script>
<?php $this->endBlock()?>

