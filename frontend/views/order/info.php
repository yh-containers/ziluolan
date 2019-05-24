<?php
$this->title = '订单提交详情';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>
<style>
    .invoice.slideTxtBox{margin:auto auto 100px;padding:0 3%;box-sizing:border-box;width:100%;text-align:left;clear:both;}
    .invoice.slideTxtBox .hd{height:30px;line-height:30px;position:relative;}
    .invoice.slideTxtBox .hd ul{margin-top:25px;}
    .invoice.slideTxtBox .hd ul li{float:left;text-align:center;padding:0 15px;width:33.333%;cursor:pointer;border:1px solid #ccc;box-sizing:border-box;margin-left:-1px;height:32px;line-height:32px;}
    .invoice.slideTxtBox .hd ul li.on{border-color:#7d1f88;background:#7d1f88;color:#fff;}
    .invoice.slideTxtBox .bd>ul{display: none;padding:15px 0;zoom:1;color:#999;font-size:12px;}
    .invoice.slideTxtBox .bd>ul.cur{display: block;}
    .choose,.add{height:100%;padding-top:20px;}
    .choose ul li {border:none;}
    .choose ul li  input[type="text"] {margin-bottom: -1px;padding:10px;border:1px solid #ccc;box-sizing:border-box;width:100%;}
    .message-div {padding-top: 10px;}
    .message-div textarea {width: 100%;border:1px solid #dcdcdc;height: 90px;padding: 5px;border-radius: 5px;margin-top: 5px;box-sizing:border-box;}

</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>


<div class="header"> <a href="javascript:window.history.go(<?=$channel?-3:-1?>)" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>

<form action="<?=\yii\helpers\Url::to(['confirm'])?>" id="form" method="post">
    <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
    <input type="hidden" name="channel" value="<?=$channel?>"/>
    <input type="hidden" name="channel_g_data" value="<?=$channel_g_data?>"/>
    <input type="hidden" name="addr_id" value="<?=$model_addr['id']?>"/>
    <input type="hidden" name="gid" value="<?=$gid?>"/>
    <input type="hidden" name="sku_id" value="<?=$sku_id?>"/>
    <input type="hidden" name="num" value="<?=$num?>"/>

    <div class="content sub-order bj1">
        <div class="daishou clearfix" id="recive_mode">
            <a class="cur" data-id="1"  href="javascript:;"><i class="on"></i>快递</a>
            <a class="" data-id="0" href="javascript:;"><i class="on"></i>自提</a>
            <input type="hidden" name="recive_mode"  value="1"/>
        </div>
        <div class="Personal address clearfix" id="address">
            <div class="list clearfix">
                <ul>
                    <?php if(!empty($model_addr)){ ?>
                        <li class="clearfix" >
                            <div class="left fl"">
                                <p><span><?=$model_addr['username']?></span><span><?=$model_addr['phone']?></span></p>
                                <p><?=$model_addr['addr'].'  '.$model_addr['addr_extra']?></p>
                            </div>
                            <a href="<?=\yii\helpers\Url::to(['mine/address','channel'=>'order'])?>">
                                <div class="right1 fr"></div>
                            </a>
                        </li>
                    <?php }else{ ?>
                        <li class="clearfix" >
                            <a href="<?=\yii\helpers\Url::to(['mine/address-add'])?>" class="addr_no">你还没有默认收货地址，点击去创建</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?php foreach($goods_info as $vo){?>
            <div class="pick">
                <div class="wrap clearfix">
                    <ul class="clearfix">

                        <li class="wow fadeInDown animate clearfix">
                            <div class="img">
                                <div class="pic fl"><a href="javascript:;"><img class="tran" src="<?=\common\models\Goods::getCoverImg($vo['linkGoods']['image'])?>"></a></div>
                                <div class="content fl">
                                    <p class="title"><a href="javascript:;"><?=$vo['linkGoods']['name']?>【<?=$vo['sku_group_name']?>】* <?=$vo['buy_num']?>份</a></p>
                                    <p class="price">单价:¥ <?=$vo['price']?>&nbsp;&nbsp; 总价:￥<?=$vo['price']*$vo['buy_num']?> </p>
                                    <p class="quantity">数量：<?=$vo['buy_num']?></p>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

        <?php }?>
            <div class="message-div">
                <div class="wrap clearfix">
                    <ul class="clearfix">
                        <li>
                            <span style="color: #7c1f87">备注:</span>
                        </li>
                        <li>
                            <textarea name="message" id="" cols="30" rows="10"></textarea>
                        </li>
                    </ul>
                </div>
            </div>
        <?php if(is_array($invoice_info)){ ?>
            <div class="slideTxtBox invoice clearfix">
                <div class="hd clearfix" >
                    <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
                    <span class="arrow"><a class="next"></a><a class="prev"></a></span>
                    <ul id="invoice">
                        <?php  foreach ($invoice_info as $key=>$vo){ ?>
                        <li class="<?=empty($key)?'on':''?>" data-id="<?=$key?>"><?=$vo['name']?></li>
                        <?php }?>
                    </ul>
                    <input type="hidden" name="fapiao" id="fapia" value="0"/>
                </div>



                <div class="bd clearfix" id="invoice-remark">
                    <?php  foreach ($invoice_info as $key=>$vo){ ?>
                        <ul class="<?=empty($key)?'cur':''?>">
                            <?=isset($vo['tip'])?$vo['tip']:''?>
                            <?php if(isset($vo['input']) && is_array($vo['input'])) {?>
                            <div class="choose">
                                <ul class="add">
                                    <?php foreach ($vo['input'] as $key=>$input){?>
                                        <li><input type="text" name="invoice[<?=$key?>]" placeholder="<?=$input['name']?>："></li>
                                    <?php }?>
                                </ul>
                            </div>
                            <?php }?>
                        </ul>
                    <?php }?>

                </div>
            </div>
        <?php }?>
</form>
<!-- 底部-->
<!-- 底部-->
<div class="footer-sub-order pro-footer clearfix">
    <div class="fl rmb">
        <span>需支付：</span>
        <span class="span1" id="settle-money"  data-total_money="￥<?=$money['money']?>(包含运费)" data-total_money_n_freight="￥<?=$money['total_money_no_freight']?>" >￥<?=$money['money']?>(包含运费)</span>
    </div>
    <div class="fr tjdd" id="tjdd"><a href="javascript:;" style="color:#fff;" id="submit">提交订单</a></div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

    $(function(){
        $("#recive_mode a").click(function(){
            if(!$(this).hasClass('cur')){
                var settle_money = '0.00';
                var recive_mode = $(this).data('id')
                $(this).parent().find('.cur').removeClass('cur')
                $(this).addClass('cur')
                $(this).parent().find('input[name="recive_mode"]').val(recive_mode)
                if(recive_mode){
                    $(this).parent().next().slideDown()
                    settle_money = $("#settle-money").data('total_money')
                }else{
                    $(this).parent().next().slideUp()
                    settle_money = $("#settle-money").data('total_money_n_freight');
                }
                $("#settle-money").text(settle_money)
            }

        })



        $("#invoice li").click(function(){
            if(!$(this).hasClass('cur')){
                var id = $(this).data('id')
                //选项
                $(this).parent().find('.on').removeClass('on')
                $(this).addClass('on');
                $(this).parent().parent().find('input[name="fapiao"]').val(id)
                //提示信息
                var index = $(this).index();
                $("#invoice-remark").find('.cur').removeClass('cur')
                $("#invoice-remark>ul").eq(index).addClass('cur')
            }


        })

        //数据提交
        $("#submit").click(function(){
            $.common.reqInfo({
                url:$("#form").attr('action'),
                type:'post',
                data:$("#form").serialize(),
            })
        })
    })



</script>
<?php $this->endBlock()?>

