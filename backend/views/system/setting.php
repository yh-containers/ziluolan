<?php

//用于显示左侧栏目选中状态
$this->params = [
    'crumb'          => ['系统管理','常规设置'],
];
?>
<?php $this->beginBlock('style'); ?>
<style>
    .textarea-block{position: relative;}
    .textarea-block i{right: 0;position: absolute;z-index: 999;font-size: 24px;color: red;cursor: pointer}
</style>
<?php $this->endBlock();?>
<?php $this->beginBlock('content'); ?>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <form class="form-horizontal" action="<?= \yii\helpers\Url::to(['setting-save'])?>"  id="normal-form">
                <input name="<?=\Yii::$app->request->csrfParam?>" type="hidden"  value="<?= Yii::$app->request->csrfToken ?>">
                <input name="type" type="hidden"  value="normal">
                <div class="box-body">

                    <div class="form-group">
                        <label class="col-md-2 control-label">站点名称：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($normal_content['name'])?$normal_content['name']:''?>" name="content[name]">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">首页标题：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($normal_content['title'])?$normal_content['title']:''?>" name="content[title]">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">LOGO：</label>
                        <div class="col-md-7">
                            <input type="hidden" name="content[image]" value="<?=isset($normal_content['image'])?$normal_content['image']:''?>"/>
                            <button class="layui-btn upload"  type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'logo'])?>',data:{ '<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >选择图片</button>
                            <img src="<?= isset($normal_content['image'])?$normal_content['image']:'' ?>" alt="LOGO" class="radius" width="80" height="80">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">站点关键字：</label>
                        <div class="col-md-10">
                            <textarea class="form-control" rows="3" name="content[key]"><?=isset($normal_content['key'])?$normal_content['key']:''?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">站点描述：</label>
                        <div class="col-md-10">
                            <textarea class="form-control" rows="3" name="content[desc]"><?=isset($normal_content['desc'])?$normal_content['desc']:''?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">页脚代码：</label>
                        <div class="col-md-10">
                            <textarea class="form-control" rows="3" name="content[footer]"><?=isset($normal_content['footer'])?$normal_content['footer']:''?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">公用联系方式：</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="content[com_tel]" value="<?=isset($normal_content['com_tel'])?$normal_content['com_tel']:''?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">公司名称：</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="content[conpamy_name]" value="<?=isset($normal_content['conpamy_name'])?$normal_content['conpamy_name']:''?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">服务热线：</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="content[tel]" value="<?=isset($normal_content['tel'])?$normal_content['tel']:''?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">网址：</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="content[url]" value="<?=isset($normal_content['url'])?$normal_content['url']:''?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">邮编：</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="content[zip_code]" value="<?=isset($normal_content['zip_code'])?$normal_content['zip_code']:''?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Email：</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="content[email]" value="<?=isset($normal_content['email'])?$normal_content['email']:''?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">地址：</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="content[addres]" value="<?=isset($normal_content['addres'])?$normal_content['addres']:''?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">wap客服QQ：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($normal_content['rightqq'])?$normal_content['rightqq']:''?>" name="content[rightqq]">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="button" class="btn btn-block btn-primary btn-flat" value=" 提交 "  id="submit"  onclick="$.common.formSubmit($('#normal-form'),1)"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <div class="col-sm-6" >
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">固定奖</h3>
            </div>
            <form class="form-horizontal" action="<?= \yii\helpers\Url::to(['setting-save'])?>"  id="fixed-form">
                <input name="<?=\Yii::$app->request->csrfParam?>" type="hidden"  value="<?= Yii::$app->request->csrfToken ?>">
                <input type="hidden" name="type"  value="fixed" />
                <div class="box-body">

                    <div class="form-group">
                        <label class="col-md-2 control-label">固定金额：</label>
                        <div class="col-md-10">
                            <input type="number" class="form-control" value="<?=$fixed?>" name="content">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="hidden" name="lang" value="1">
                            <input type="button" class="btn btn-block btn-primary btn-flat" value=" 提交 "  id="submit"  onclick="$.common.formSubmit($('#fixed-form'),1)"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">推荐奖金设置</h3>
                <span class="text-red">(请设置0-1之间的数字)</span>
            </div>
            <form class="form-horizontal" action="<?= \yii\helpers\Url::to(['setting-save'])?>"  id="recommend-form">
                <input name="<?=\Yii::$app->request->csrfParam?>" type="hidden"  value="<?= Yii::$app->request->csrfToken ?>">
                <input type="hidden" name="type"  value="recommend" />
                <div class="box-body">

                    <div class="form-group">
                        <label class="col-md-2 control-label">一级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($recommend[0])?$recommend[0]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">二级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($recommend[1])?$recommend[1]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">三级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($recommend[2])?$recommend[2]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">四级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($recommend[3])?$recommend[3]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">五级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($recommend[4])?$recommend[4]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">六级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($recommend[5])?$recommend[5]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">七级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($recommend[6])?$recommend[6]:''?>" name="content[]">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="hidden" name="lang" value="1">
                            <input type="button" class="btn btn-block btn-primary btn-flat" value=" 提交 "  id="submit"  onclick="$.common.formSubmit($('#recommend-form'),1)"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">团队奖</h3>
                <span class="text-red">(请设置0-1之间的数字)</span>
            </div>
            <form class="form-horizontal" action="<?= \yii\helpers\Url::to(['setting-save'])?>"  id="group-form">
                <input name="<?=\Yii::$app->request->csrfParam?>" type="hidden"  value="<?= Yii::$app->request->csrfToken ?>">
                <input type="hidden" name="type"  value="group_award" />
                <div class="box-body">

                    <div class="form-group">
                        <label class="col-md-2 control-label">一级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($group_award[0])?$group_award[0]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">二级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($group_award[1])?$group_award[1]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">三级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($group_award[2])?$group_award[2]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">四级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($group_award[3])?$group_award[3]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">五级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($group_award[4])?$group_award[4]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">六级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($group_award[5])?$group_award[5]:''?>" name="content[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">七级：</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=isset($group_award[6])?$group_award[6]:''?>" name="content[]">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="button" class="btn btn-block btn-primary btn-flat" value=" 提交 "  id="submit"  onclick="$.common.formSubmit($('#group-form'),1)"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</div>

<?php $this->endBlock(); ?>
<?php $this->beginBlock('script'); ?>
<script>
    <!-- 实例化编辑器 -->
    layui.use(['upload'], function(){
        var upload = layui.upload;

        $.common.uploadFile(upload,'.upload')

    });
    $(function(){
        $("#add-line").click(function(){
            $("#problem-block").append('<div class="textarea-block">\n' +
                '                                <i class="fa fa-fw fa-close"></i>\n' +
                '                                <textarea name="content[]" class="textarea margin-bottom layui-textarea"></textarea>\n' +
                '                            </div>');
        })
        $("#problem-block").on('click','.textarea-block i',function(){
            $(this).parent().remove()
        })
    })
</script>
<?php $this->endBlock(); ?>

