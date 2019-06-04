<?php
$this->title = '账户余额';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>
    .posF {display: none;height: 100%;left: 0; position: fixed; top: 0;width: 100%; z-index: 5;}

    .posF .bg { background: #000 none repeat scroll 0 0; height: 100%;
        left: 0;
        opacity: 0.35;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: -1;
    }
    .posF .box500 {
        background: #fff none repeat scroll 0 0;
        border-radius: 6px;

        margin: 30% auto 0;

        width: 70%;

    }

    .posF .box500 .hd {

        border-bottom: 2px solid #dfdfdf;

        position: relative;

    }

    .posF .box500 .hd p {

        color: #535353;

        font-size: 20px;

        line-height: 50px;

        margin: 0;

        text-indent: 2em;

    }

    .posF .box500 .hd .off {

        background: rgba(0, 0, 0, 0) url("<?=\Yii::getAlias('@assets')?>/images/icon_index.png") no-repeat scroll -230px -350px;

        cursor: pointer;

        display: block;

        height: 33px;

        margin-top: -16.5px;

        position: absolute;

        right: 15px;

        top: 50%;

        width: 35px;

    }

    .posF .bd {

        padding: 30px;

    }

    .posF .rope {

        /*  margin: 0 25px 10px;*/

        padding: 0;

        text-align: center;

    }

    .posF .rope span {

        color: #535353;

        display: inline-block;

        width: 70px;

    }

    .posF .rope select {

        border: 1px solid #e5e5e5;

        color: #333;

        height: 36px;

        outline: medium none;

        width: 310px;

    }
    .posF .rope input {
        border: 1px solid #e5e5e5;
        color: #333;
        height: 34px;
        line-height: 34px;
        outline: medium none;
        padding: 0 7px;
        width: 100%;
        margin-top: 2%;
        margin-bottom: 2%;
    }
    .posF .rope button.off {
        background-color: #b5b5b5;
        color: #fff;
        margin-left: 0;
    }
    .posF .rope .submit {
        background: #f10215 none repeat scroll 0 0;
        color: #fff;
    }
    .posF .rope a {
        border: 0 none;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
        font-family: "Microsoft Yahei";
        font-size: 14px;
        height: 36px;
        line-height: 36px;
        /* margin-left: 23px;*/
        outline: medium none;
        padding: 0 15px;
        width: 105px;}
    .show1{display: block;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header"> <a href="<?=\yii\helpers\Url::to(['index'])?>" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<div class="content  sub-order bj1">
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
                <span class="fl"><i class="left icon3"></i>   健康豆 ：<?=$user_model['deposit_money']?></span>
            </li>

            </li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>团队业绩：<i style="color:#f14141;padding-left:20px;"><?=$user_model['team_wallet_full']?></i></span></li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>团队提成：<i style="color:#f14141;padding-left:20px;"><?=$user_model['team_wallet']?></i></span></li>
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
        <div class="hd posR">
            <p>请输入兑换信息</p>
            <span class="off posA" onclick="g_fh_offs(this)">&nbsp;</span>
        </div>
        <form action="<?=\yii\helpers\Url::to(['dw2-wa'])?>">
            <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="bd">
                <div class="rope conts" style="">
                    <p class="fl txt" style="margin-left:2%;width:100%">健康豆：<span style="color: red"><?php echo $user_model['deposit_money'];?></span></p>
                    <input class="fr txt jk_price" name="number"  type="number" placeholder="请填写兑换的健康豆数"/>
                    <p style="font-size: 12px;color: red;">健康豆兑换金豆，兑换率为：1:<?=\common\models\User::DEPOSIT_2_WALLET_PER?> (金豆可提现)</p>
                </div>
                <div class="rope" style="margin-top:10px">

                    <a href="javascript" style="margin-top:20px; background:#990098" class="submit sure-commit">确定</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="posF" id="give" >
    <div class="bg"></div>
    <div class="box500">
        <div class="hd posR">
            <p>请输入赠送信息</p>
            <span class="off posA" onclick="t_fh_offs(this)">&nbsp;</span>
        </div>
        <form action="<?=\yii\helpers\Url::to(['give-user'])?>">
            <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="bd">

                <div class="rope conts" style="">
                    <p class="fl txt" style="margin-left:2%;">我的金豆：<span style="color: red"><?=$user_model['wallet']?></span></p>
                    <input class="fr txt zs_price"  type="number" name="number" placeholder="请填写赠送金豆数"/>
                    <input class="fr txt zs_number" type="text" name="user_number" placeholder="请输入赠送者会员号"/>
                </div>
                <div class="rope" style="margin-top:10px">
                    <a href="javascript" style="margin-top:20px; background:#990098" class="submit sure-commit">确定</a>
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
</script>
<?php $this->endBlock()?>

