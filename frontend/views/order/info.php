<?php
$this->title = '订单提交详情';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>
<style>
   body {background: #fff}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>


<div class="header">
    <a href="javascript:window.history.go(<?=$channel?-3:-1?>)" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
</div>


<form action="<?=\yii\helpers\Url::to(['confirm'])?>" id="form" method="post">
    <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
    <input type="hidden" name="channel" value="<?=$channel?>"/>
    <input type="hidden" name="channel_g_data" value="<?=$channel_g_data?>"/>
    <input type="hidden" name="addr_id" value="<?=$model_addr['id']?>"/>
    <input type="hidden" name="gid" value="<?=$gid?>"/>
    <input type="hidden" name="sku_id" value="<?=$sku_id?>"/>
    <input type="hidden" name="num" value="<?=$num?>"/>

    <div class="content sub-order">
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
                            <a href="<?=\yii\helpers\Url::to(['mine/address-add'])?>" class="addr_no">
                                <span>你还没有默认收货地址，点击去创建</span>
                                <div class="right1 fr"></div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?php foreach($goods_info as $vo){?>
            <div class="pick" >
                <ul class="clearfix">
                    <li class="clearfix">
                        <div class="img">
                            <div class="pic fl"><a href="javascript:;"><img class="tran" src="<?=\common\models\Goods::getCoverImg($vo['linkGoods']['image'])?>"></a></div>
                            <div class="content fl">
                                <div class="title"><a href="javascript:;"><?=$vo['linkGoods']['name']?>【<?=$vo['sku_group_name']?>】* <?=$vo['buy_num']?>份</a></div>
                                <p class="price">单价:¥ <?=$vo['price']?>&nbsp;&nbsp; 总价:￥<?=$vo['price']*$vo['buy_num']?> </p>
                                <p class="quantity">数量：<?=$vo['buy_num']?></p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        <?php }?>


            <div class="choose-operate">
                <p>您的账户当前共<?=$user_model['consum_wallet']?>消费金豆</p>
                <div class="oItem clearfix">
                    <div class="itemName">消费金豆：</div>
                    <div class="itemInfo">
                        <input type="number" name="inv_pear" value="0" />
                    </div>
                </div>
            </div>

            <div class="message-div">
                <div class="clearfix">
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
                                    <?php foreach ($vo['input'] as $d_key=>$input){?>
                                        <li><input type="text" name="invoice[<?=$key?>][<?=$d_key?>]" placeholder="<?=$input['name']?>："></li>
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

