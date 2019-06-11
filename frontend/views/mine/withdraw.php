<?php
$this->title = '账户余额';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>
    body {background-color: #fff;}
    .posF {display: none;position: fixed; top: 0;left: 0; width: 100%;height: 100%; z-index: 5;} 
    .posF .bg {background: #000 none repeat scroll 0 0; height: 100%; left: 0; opacity: 0.35; position: absolute; top: 0; width: 100%; z-index: -1; } 
    .posF .box500 {background: #fff none repeat scroll 0 0; border-radius: 6px; margin: 30% auto 0; width: 84%; } 
    .posF .box500 .hd {border-bottom: 1px solid #dfdfdf; position: relative;padding: 7px 20px; } 
    .posF .box500 .hd p {color: #353535; font-size: 1rem; line-height: 30px;float: left;} 
    .posF .box500 .hd .off {float: right;width: 30px;height: 30px;border-radius: 30px;background: url("<?=\Yii::getAlias('@assets')?>/images/guanbi.png") no-repeat right center;background-size: 21px;} 
    .posF .bd {padding: 20px 20px 30px;} 
    
    .posF .conts .txt {color: #333;font-size: 16px;margin-bottom: 5px} 
    .posF .conts select {border: 1px solid #e5e5e5; color: #333; height: 36px; outline: medium none; width: 310px; } 
    .posF .conts input {border: 1px solid #e5e5e5; color: #333; height: 34px; line-height: 24px; outline: medium none; padding: 5px 10px; width: 100%; box-sizing: border-box;margin:5px 0px;} 
    .posF .conts button.off {background-color: #b5b5b5; color: #fff; margin-left: 0; } 
    .posF .conts p {color: #f00;font-size: 12px;}
    .posF .rope {margin-top: 30px } 
    .posF .rope a {display: block;width: 100%;height: 40px;line-height: 40px;border-radius: 6px;font-size: 1rem;background-color: #960095;color: #fff;text-align: center;}
    .show1{display: block;}    
    .bank {padding: 15px 0px;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header"> <a href="<?=\yii\helpers\Url::to(['index'])?>" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="content sub-order">
    <!-- -->
    <div class="bank clearfix">

        <ul class=" wrap">
            <li class="clearfix">
                <span class="fl"><i class="left icon3"></i>我的金豆：<?=$user_model['wallet']?></span>
            </li>
            <li class="clearfix">
                <span class="fl"><i class="left icon3"></i>消费金豆：<?=$user_model['consum_wallet']?></span>
            </li>
            <li class="clearfix">
                <span class="fl"><i class="left icon3"></i>健 康 豆 ：<?=$user_model['deposit_money']?></span>
            </li>

            </li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>团队业绩：<i style="color:#f14141;"><?=$user_model['team_wallet_full']?></i></span></li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>团队提成：<i style="color:#f14141;"><?=$user_model['team_wallet']?></i></span></li>
        </ul>
    </div>


    <?php if(!empty($withdraw_money)){?>
    <label class="clearfix">
        <div class="jine clearfix" style="text-align:center;color:red;margin-top:2%;">提现中*** （<?=$withdraw_money?>）</div>
    </label>
    <?php }?>


    <p style="color: red;margin:0px;text-align: center;">金豆兑换需收取<?=\common\models\User::WITHDRAW_MONEY_COM_PER*100?>%手续费</p>
    <p style="color: red;margin:0px;text-align: center;">申请兑换后三个工作日内到账</p>




    <a href="<?=\yii\helpers\Url::to(['withdraw-money'])?>"><div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix">金豆提现</div></a>

    <div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix charge" data-node="give">金豆赠送</div>
    <div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix charge" data-node="health">健康豆兑换金豆</div>
    <a href="<?=\yii\helpers\Url::to(['money-log'])?>"><div style="margin-top:4%;margin-bottom:4%;background:#828181;" class="withdraw clearfix">查看明细</div></a>
</div>

<div class="posF" id="health" >
    <div class="bg"></div>
    <div class="box500">
        <div class="hd clearfix">
            <p>请输入兑换信息</p>
            <span class="off posA" onclick="g_fh_offs(this)"></span>
        </div>
        <form action="<?=\yii\helpers\Url::to(['dw2-wa'])?>">
            <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="bd">
                <div class="conts">
                    <div class="txt">健康豆：<span style="color: red"><?php echo $user_model['deposit_money'];?></span></div>
                    <input class="jk_price" name="number"  type="number" placeholder="请填写兑换的健康豆数"/>
                    <p>健康豆兑换金豆，兑换率为：1:<?=\common\models\User::DEPOSIT_2_WALLET_PER?> (金豆可提现)</p>
                </div>
                <div class="rope">

                    <a href="javascript:;"class="submit sure-commit">确定</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="posF" id="give" >
    <div class="bg"></div>
    <div class="box500">
        <div class="hd clearfix">
            <p>请输入赠送信息</p>
            <span class="off posA" onclick="t_fh_offs(this)"></span>
        </div>
        <form action="<?=\yii\helpers\Url::to(['give-user'])?>">
            <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="bd">
                <div class="conts">
                    <div class="txt">我的金豆：<span style="color: red"><?=$user_model['wallet']?></span></div>
                    <input class="zs_price"  type="number" name="number" placeholder="请填写赠送金豆数"/>
                    <input class="zs_number" type="text" name="user_number" placeholder="请输入赠送者会员号"/>
                </div>
                <div class="rope">
                    <a href="javascript:;" class="submit sure-commit">确定</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
$(function(){
    $(".charge").click(function () {
        var open_node = '#'+$(this).data('node')
        $(open_node).show()
    })
    $(".sure-commit").click(function(){
        $.common.reqInfo({
            url:$(this).parents('form').attr('action'),
            type:'post',
            data:$(this).parents('form').serialize(),
            success:function(res){
                layui.layer.msg(res.msg)
                if(res.code==1){
                    setTimeout(function(){window.location.reload()},1000)
                }
            }
        })
        return false;
    })
})
function g_fh_offs(_this){
    $(".posF").hide();
}
function t_fh_offs(_this){
    $(".posF").hide();
}
</script>
<?php $this->endBlock()?>

