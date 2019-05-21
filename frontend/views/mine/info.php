<?php
$this->title = '个人中心';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>
    .form-group {border-bottom:1px solid #ececec;}
    .express-area {
        height: 45px;
        width: 100%;
        position: relative;
        border-bottom: 1px solid #f5f5f5;
    }
    .name {
        width: 80px;
        line-height: 45px;
        display: block;
        font-size: 0.9rem;
        color: #555;
    }
    .express-area select {
        outline: none;
        width: calc(100% - 100px);
        padding: 0px;
        margin-left: -10px;
    }

    .form-group div.name {
        width: 100%;
        line-height: 45px;
        display: block;
        font-size: 0.95rem;
        color: #333;
        padding: 0 10px;box-sizing:border-box;
    }
    .js_uploadBox {padding: 0px 10px 15px;}
    .btn-upload {
        width: 100%;
        height: 32px;
        position: relative;
        margin-bottom: 10px;
    }
    .btn-upload a {
        display: block;
        width: 100%;
        line-height: 18px;
        padding: 6px 0;
        text-align: center;
        color: #4c4c4c;
        background: #fff;
        border: 1px solid #cecece;
    }
    .btn-upload input {
        width: 100% !important;
        height: 32px !important;
        position: absolute;
        left: 0px;
        top: 0px;
        z-index: 1;
        filter: alpha(opacity=0);
        -moz-opacity: 0;
        opacity: 0;
        cursor: pointer;
    }
    .icon-upload {
        display: inline-block;
        width: 17px;
        height: 17px;
        background: url(./icons.png) -78px 0 no-repeat;
        vertical-align: middle;
        margin-right: 5px;
        background-position: -144px -24px;
    }
    .js_showBox {width: 100%;height: 120px;line-height: 120px;color: #333;text-align: center;border:1px solid #c4c4c4;border-radius: 3px;overflow: hidden;background-color: #fff;}
    .js_showBox img {width: 100% !important;}
    .mh {max-height: 100%;}
    .mw {max-width: 100%;}
    .album-old .upload-img {width: 456px;height: 344px;}
    .album-new .upload-img {width: 456px;height: 344px;}
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
            <form id="UserModel" class="" action="/m/info.html" method="post"  enctype="multipart/form-data">
                <ul>

                    <li class="clearfix"><span class="span1">姓名</span>
                        <input type="text" name="usersname" class="usersname" value="<?=$user_model['usersname']?>" placeholder="（必填项）" />
                    </li>
                    <li class="clearfix"><span class="span1">昵称</span>
                        <input type="text" name="username" value="<?=$user_model['username']?>" />
                    </li>
                    <li class="clearfix">
                        <span class="span1">性别</span>
                        <input type="radio" name="sex" value="0" <?php if($user_model['sex']==0){echo ' checked=""'; }?> style="top:3px;position:relative;margin-right:5px;"  checked="" >保密
                        <input type="radio" name="sex" value="1" <?php if($user_model['sex']==1){echo ' checked=""'; }?> style="top:3px;position:relative;margin-right:5px;"  >男
                        <input type="radio" name="sex" value="2" <?php if($user_model['sex']==2){echo ' checked=""';}?>  style="top:3px;position:relative;margin-right:5px;"  >女

                    </li>
                    <li class="clearfix"> <span class="span1">生日</span>
                        <input type="text" id="dateSelectorOne" name="birthday" value="<?=$user_model['birthday']?>"  placeholder="选择日期（必填项）">
                    </li>
                    <li class="clearfix"> <span class="span1">城市地区</span>
                        <input type="text" name="address" id="sel_city" value="<?=$user_model['address']?>" placeholder="填写城市地区（必填项）" />
                    </li>
                    <li class="clearfix"> <span class="span1">微信号</span>
                        <input type="text"  name="weixin" value="<?=$user_model['weixin']?>"  placeholder="微信号">
                    </li>
                    <li class="clearfix"> <span class="span1">手机</span>
                        <input type="text"  name="mobile" id="mobile" value="<?=$user_model['mobile']?>"  placeholder="手机号码（必填项）">
                    </li>


        </div>
    </div>

    </ul>






    <div class=""><a id="bti-a" style="display: block;width: 130px; height: 35px; line-height: 35px;text-align: center;background: #685F84;color: #fff; font-size: 14px;margin: 30px auto 0;transition: all 0.3s ease;" href="javascript:;" onclick="ongetinfo()">保存</a></div>
    </form>
    <input type="hidden" id="subtype" value="{$big}" />
</div>
</div>

<?=\frontend\widgets\Footer::widget(['current_action'=>'mine'])?>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/js/jquery.flexslider-min.js"></script>
<script>


</script>
<?php $this->endBlock()?>

