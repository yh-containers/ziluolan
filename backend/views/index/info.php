<?php
$this->params=[
        'current_active'=>['index','index/info']
];
?>
<?php $this->beginBlock('content')?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">网站信息</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab">网站基本信息</a></li>

        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="home">
                <div class="box-body">
                    <!-- 中文网站信息 -->
                    <form class="form-horizontal"  id="form">
                        <input name="<?=\Yii::$app->request->csrfParam?>" type="hidden"  value="<?= Yii::$app->request->csrfToken ?>">
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-md-2 control-label">站点名称：</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="<?=$model['name']?>" name="name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">首页标题：</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="<?=$model['title']?>" name="title">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">LOGO：</label>
                                <div class="col-md-7">
                                    <input type="hidden" name="image" value="<?=$model['image']?>"/>
                                    <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'article'])?>',data:{ '<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >选择图片</button>
                                    <img src="<?= $model['image'] ?>" alt="LOGO" class="radius" width="80" height="80">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">站点关键字：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="3" name="key"><?=$model['key']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">站点描述：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="3" name="desc"><?=$model['desc']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">页脚代码：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="3" name="footer"><?=$model['footer']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">公用联系方式：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="3" name="contact"><?=$model['contact']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">公司名称：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="1" name="contact2"><?=$model['contact2']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">服务热线：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="1" name="contact3"><?=$model['contact3']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">网址：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="1" name="contact4"><?=$model['contact4']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">邮编：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="1" name="contact5"><?=$model['contact5']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Email：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="1" name="email"><?=$model['email']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">地址：</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="1" name="addres"><?=$model['addres']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">wap客服QQ：</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="<?=$model['rightqq']?>" name="rightqq">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">微商城服务协议：</label>
                                <div class="col-md-8">
                                    <script id="container" name="fuwuxy" type="text/plain"><?=$model['fuwuxy']?></script>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-6">
                                    <input type="hidden" name="lang" value="1">
                                    <input type="button" class="btn btn-block btn-primary btn-flat" value=" 提交 "  id="submit"  onclick="$.common.formSubmit($('#form'),1)"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>




        </div>
    </div>

</div>
<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor/ueditor.config.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor/ueditor.all.js"></script>
<script>
    var ue = UE.getEditor('container',{
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo','inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts','|','simpleupload'],
            ['lineheight','|','customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','directionalityltr', 'directionalityrtl', 'indent', '|','justifyleft', 'justifycenter', 'justifyright', 'justifyjustify'],
            ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
        ]
    });
    $(function(){
        layui.use(['upload'], function(){
            var upload = layui.upload;

            $.common.uploadFile(upload,'#test1')

        });
    })
</script>
<?php $this->endBlock()?>
