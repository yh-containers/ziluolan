<?php
$this->title = '订单结算';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>


<?php $this->endBlock()?>

<?php $this->beginBlock('content')?>

<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo">结算</div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>
<div class="clearfix" style="height:60px;"> </div>

<form action="" id="form">
    <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
    <input type="hidden" name="pay_way" value="0">
    <input type="hidden" name="id"  value="<?=$model['id']?>">
    <div class="content sub-order bj1">

        <div class="bank clearfix wrap">
            <ul>
                <li class="clearfix cur zffsn" data-pay_way="0"><span class="fl"><i class="left icon1"></i>微信支付</span><i class="on fr"></i></li>
                <li class="clearfix zffsn" data-pay_way="1"><span class="fl"><i class="left icon2"></i>线下支付</span><i class="on fr"></i></li>
                <li class="clearfix zffsn"  data-pay_way="2"><span class="fl"><i class="left icon3"></i>我的余额：<i class="price">￥<?= $user_model['wallet'];?></i></span><i class="on fr"></i></li>
            </ul>
        </div>
    </div>

    <div class="footer-sub-order pro-footer clearfix">

            <div class="fl rmb"><span>需支付：</span><span class="span1">￥<?=$model['pay_money']?></span></div>
            <div class="fr tjdd" id="tjdd"><a href="javascript:;" id="submit" style="color:#fff;">支付</a></div>
    </div>
</form>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    //支付信息
    var pay_parameters={};
    //调用微信JS api 支付
    function jsApiCall()
    {
        console.log(typeof pay_parameters)
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            pay_parameters,
            function(res){
                switch (res.err_msg) {
                    case 'get_brand_wcpay_request:ok':
                        alert('支付成功');
                        break;
                    case 'get_brand_wcpay_request:cancel':
                        alert('已取消支付');
                        break;
                    case 'get_brand_wcpay_request:fail':
                    default:
                        alert('支付失败');
                        break;
                }
                // WeixinJSBridge.log(res.err_msg);
                // alert(res.err_code+res.err_desc+res.err_msg);
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }

$(function(){
    $(".zffsn").click(function () {
        var pay_way = $(this).data('pay_way')
        $(this).addClass('cur').siblings().removeClass('cur')
        $("input[name='pay_way']").val(pay_way)
    })
    $("#submit").click(function(){
        //其它支付
        $.common.reqInfo({
            url:$("#form").attr('action'),
            type:'post',
            data:$("#form").serialize(),
            success:function(res){
                if(res.code===2){
                    pay_parameters = res.parameters
                    if(typeof pay_parameters==='string'){
                        pay_parameters=JSON.parse(pay_parameters);
                    }
                    callpay()
                }else{
                    layui.layer.msg(res.msg)
                }
                if(res.code==1 || res.code==2){
                    setTimeout(function(){window.location.href="<?=\yii\helpers\Url::to(['order/detail','id'=>$model['id']])?>"},100)

                }
            }
        })
    })
})

</script>
<?php $this->endBlock()?>

