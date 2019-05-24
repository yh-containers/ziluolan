<?php
$this->title = '个人中心';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>
#submit{display: block;width: 130px; height: 35px; line-height: 35px;text-align: center;background: #685F84;color: #fff; font-size: 14px;margin: 30px auto 0;transition: all 0.3s ease;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header">
    <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<div class="content">
    <div class="user_wrap">
        <form class="form-horizontal clearfix" method="post" action="" id="form">
            <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="id" value="<?=$model['id']?>">
            <div class="addresslist">
                <div class="form-group username">
                    <label class="name">收货人姓名</label>
                    <input type="text" name="username" placeholder="收货人姓名" maxlength="15" value="<?=$model['username']?>">
                </div>
                <div class="form-group mobileno">
                    <label class="name">联系电话</label>
                    <input type="text" name="phone" placeholder="11位数手机号" maxlength="11" value="<?=$model['phone']?>">
                </div>


                <div class="form-group detailed-addr">
                    <label class="name">地址</label>
                    <input type="text" readonly id="J_Address" name="addr" value="<?=$model['addr']?>" placeholder="城市地区">
                </div>

                <div class="form-group detailed-addr">
                    <label class="name">详细地址</label>
                    <input type="text" name="addr_extra" placeholder="详细地址" maxlength="60" value="<?=$model['addr_extra']?>">
                </div>
                <div class="form-group detailed-addr">
                    <label class="name">邮编</label>
                    <input type="text" name="zip_code" placeholder="邮政编码" maxlength="20"  value="<?=$model['zip_code']?>">
                </div>

                <div class="form-cur">
                    <a class="btnSetDefault <?=$model['is_default']?'cur':''?>" href="javascript:;"></a>设为默认地址
                    <input type="hidden" value="<?=$model['is_default']?1:0?>"  name="is_default"/>
                </div>
                <a id="submit"  class="disabled">保存地址</a> </div>
        </form>
    </div>

    <!-- 底部-->
    <!-- 底部-->
    <div class="clearfix" style="height:100px;"> </div>
</div>


<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<link rel="stylesheet" href="<?=\Yii::getAlias('@assets')?>/css/ydui.css">
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/js/ydui.citys.js"></script>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/js/ydui.js"></script>
<script>
    var $address = $('#J_Address');

    $address.citySelect();

    $address.on('click', function () {
        $address.citySelect('open');
    });

    $address.on('done.ydui.cityselect', function (ret) {
        /* 省：ret.provance */
        /* 市：ret.city */
        /* 县：ret.area */
        $(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
    });
    $(function(){
        layui.use(['layer'],function(){
            var layer = layui.layer;
            //提交数据
            $("#submit").click(function(){
                var req_data = {}
                $("#form").serializeArray().map(function(item,index){
                    req_data[item.name] = item.value
                })
                console.log(req_data);
                $.common.reqInfo({
                    url:$("#form").attr('action'),
                    type:'POST',
                    data:req_data,
                    success:function(res){
                        layer.msg(res.msg)
                        if(res.code === 1){
                            setTimeout(function(){history.go(-1)},1000)
                        }
                    }
                })
            })

            //设为默认地址
            $(".btnSetDefault").click(function(){
                var is_check = $(this).hasClass('cur');
                if(is_check){
                    $(this).removeClass('cur')
                }else{
                    $(this).addClass('cur')
                }
                $(this).parent().find('input').val(is_check?0:1)
            })

        })


    })
</script>
<?php $this->endBlock()?>

