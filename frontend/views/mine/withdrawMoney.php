<?php
$this->title = '订单提交详情';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>
<style>
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

<form id="form" method="post">
    <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
    <input type="hidden" name="bank_id" value="<?=$model_bank['id']?>"/>
    <div class="content sub-order bj1">

        <div class="Personal address clearfix" id="address">
            <div class="list clearfix">
                <ul>
                    <?php if(!empty($model_bank)){ ?>
                        <li class="clearfix" >
                            <div class="left fl"">
                                <p><span><?=$model_bank['name']?></span><span><?=$model_bank['number']?></span></p>
                                <p><?=$model_bank['username'].'  '.$model_bank['phone']?></p>
                            </div>
                            <a href="<?=\yii\helpers\Url::to(['bank-card','channel'=>'order'])?>">
                                <div class="right1 fr"></div>
                            </a>
                        </li>
                    <?php }else{ ?>
                        <li class="clearfix" >
                            <a href="<?=\yii\helpers\Url::to(['bank-card'])?>" class="addr_no">你还没有绑定银行卡,前往绑定</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>


            <div class="message-div">
                <div class="wrap clearfix">
                    <div class="form-group mobileno">
                        <label class="name">提现金额</label>
                        <input type="text" name="number" placeholder="<?=$user_model['wallet']?>" maxlength="50" value="">
                    </div>
                </div>
            </div>

</form>
<!-- 底部-->
<!-- 底部-->
<div class="footer-sub-order pro-footer clearfix">

    <div class="fr tjdd" id="tjdd"><a href="javascript:;" style="color:#fff;" id="submit">提交订单</a></div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

    $(function(){


        //数据提交
        $("#submit").click(function(){
            $.common.reqInfo({
                url:$("#form").attr('action'),
                type:'post',
                data:$("#form").serialize(),
                success:function (res) {
                    layui.layer.msg(res.msg)
                    if(res.code==1){
                        setTimeout(function(){location.reload()},1000)
                    }
                }
            })
        })
    })



</script>
<?php $this->endBlock()?>

