<?php
$this->title = '银行卡操作';
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
                    <label class="name">开户银行</label>
                    <input type="text" name="name" placeholder="开户银行" maxlength="100" value="<?=$model['name']?>">
                </div>
                <div class="form-group username">
                    <label class="name">银行卡号</label>
                    <input type="text" name="number" placeholder="银行卡号" maxlength="100" value="<?=$model['number']?>">
                </div>
                <div class="form-group mobileno">
                    <label class="name">手机号</label>
                    <input type="text" name="phone" placeholder="手机号" maxlength="11" value="<?=$model['phone']?>">
                </div>

                <div class="form-group mobileno">
                    <label class="name">银行户主</label>
                    <input type="text" name="username" placeholder="银行户主" maxlength="50" value="<?=$model['username']?>">
                </div>
                <a id="submit"  class="disabled">保存</a> </div>
        </form>
    </div>

    <!-- 底部-->
    <!-- 底部-->
    <div class="clearfix" style="height:100px;"> </div>
</div>


<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

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
                            setTimeout(function(){history.back()},1000)
                        }
                    }
                })
            })




        })


    })
</script>
<?php $this->endBlock()?>

