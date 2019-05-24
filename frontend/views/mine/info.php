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

<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<div class="content">
    <div class="Personal info clearfix">
        <div class="list clearfix">
            <ul>
                <!--<li class="clearfix">
                  <div class="left fl">头像</div>
                  <a href="javascript:;">
                  <div class="right fr"></div>
                  </a>
                </li>-->
                <li class="clearfix"><span class="span1">会员号</span><?=$user_model['number']?> </li>
            </ul>
        </div>
        <div class="list clearfix">
            <form  class="" action="" id="form" >
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <ul>

                    <li class="clearfix"><span class="span1">姓名</span>
                        <input type="text" name="usersname" class="usersname" value="<?=$user_model['usersname']?>" placeholder="（必填项）" />
                    </li>
                    <li class="clearfix"><span class="span1">昵称</span>
                        <input type="text" name="username" value="<?=$user_model['username']?>" />
                    </li>
                    <li class="clearfix">
                        <span class="span1">性别</span>
                        <input type="radio" name="sex" value="0" <?=empty($user_model['sex'])?'checked':''?>   checked="" >保密
                        <input type="radio" name="sex" value="1" <?=$user_model['sex']==1?'checked':''?> >男
                        <input type="radio" name="sex" value="2" <?=$user_model['sex']==2?'checked':''?> >女

                    </li>
                    <li class="clearfix"> <span class="span1">生日</span>
                        <input type="text" id="laydate" name="birthday" value="<?=$user_model['birthday']?>"  placeholder="选择日期">
                    </li>
                    <li class="clearfix"> <span class="span1">城市地区</span>
                        <input type="text" readonly id="J_Address" name="address" value="<?=$user_model['address']?>" placeholder="城市地区">
                    </li>
                    <li class="clearfix"> <span class="span1">微信号</span>
                        <input type="text"  name="weixin" value="<?=$user_model['weixin']?>"  placeholder="微信号">
                    </li>
                    <li class="clearfix"> <span class="span1">手机</span>
                        <input type="text"  name="phone" value="<?=$user_model['phone']?>"  placeholder="手机号码">
                    </li>

                </ul>

                <div class=""><a id="submit" style="" href="javascript:;" >保存</a></div>
            </form>
        </div>
    </div>

</div>

<?=\frontend\widgets\Footer::widget(['current_action'=>'mine'])?>

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
        layui.use(['layer','laydate'],function(){
            var layer = layui.layer;
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#laydate' //指定元素
            });

            $("#submit").click(function(){
                var req_data = {}
                $("#form").serializeArray().map(function(item,index){
                    req_data[item.name] = item.value
                })
                console.log(req_data);
                $.common.reqInfo({
                    url:$("#form").attr('action'),
                    type:'POST',
                    data:req_data
                })
            })
        })


    })
</script>
<?php $this->endBlock()?>

