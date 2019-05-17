<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','栏目管理','栏目操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">栏目操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">所属导航栏</label>

                <div class="col-sm-8">
                    <select class="form-control" name="pid">
                        <option value="0">顶级栏目</option>
                        <?php foreach ($nav_page as $vo){?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['pid']?'selected':''?>><?=$vo['name']?></option>
                            <?php foreach ($vo['linkNavPage'] as $item){?>
                                <option value="<?=$item['id']?>" <?=$item['id']==$model['pid']?'selected':''?>>&nbsp;&nbsp;&nbsp;&nbsp;┡━<?=$item['name']?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">导航栏名</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="50" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="导航栏名">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">路由</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="50" class="form-control" name="route" value="<?= $model['route'] ?>" placeholder="路由">
                    <span class="help-block">路由规则:举例：goods/search|cid=7&keyword=商品名  如上 将地址请求<em><?=\Yii::$app->request->hostInfo?>/goods/search?cid=7&keyword=商品名</em></span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">栏目属性</label>

                <div class="col-sm-8">
                    <?php
                        $nav_prop = \common\models\SysNavPage::getPropInfo('nav_prop');
                        if(is_array($nav_page))
                            foreach ($nav_prop as $key=>$vo){

                    ?>
                    <label>
                        <input type="radio" name="type"  value="<?=$key?>" <?= (empty($model) && empty($key)) ?'checked' :($model['type']==$key?'checked':'') ?>>
                        <?=$vo['name']?>
                    </label>
                    <?php }?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">图片：</label>
                <div class="col-md-7">
                    <input type="hidden" name="image" value="<?=$model['image']?>"/>
                    <button class="layui-btn upload"  type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'nav'])?>',data:{ '<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >选择图片</button>
                    <img src="<?= $model['image'] ?>" alt="图片" class="radius" width="80" height="80">
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">SEO关键字</label>

                <div class="col-sm-8">
                    <textarea type="text" maxlength="255" class="form-control" name="key" rows="5"  placeholder="SEO关键字"><?= $model['key'] ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">SEO描述</label>

                <div class="col-sm-8">
                    <textarea type="text" maxlength="255" class="form-control" name="desc" rows="5"  placeholder="SEO描述"><?= $model['desc'] ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-8">
                    <input type="number" class="form-control" name="sort" value="<?= empty($model)?100:$model['sort']?>" placeholder="">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-8">
                    <div class="radio">
                        <label>
                            <input type="radio" name="status"  value="1" <?= $model['status']!=2?'checked':'' ?>>
                            正常
                        </label>
                        <label>
                            <input type="radio" name="status" value="2" <?= $model['status']==2?'checked':'' ?>>
                            关闭
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">栏目介绍</label>

                <div class="col-sm-8">
                    <script id="container" name="content" type="text/plain"><?=$model['content']?></script>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="button" class="btn btn-info col-sm-offset-2 col-sm-8 col-xs-12" id="submit"  onclick="$.common.formSubmit(null,<?=$model['id']?0:1?>)">保存</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>


<?php $this->endBlock(); ?>

<?php $this->beginBlock('script');?>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.config.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.all.js"></script>
<script>
    var ue = UE.getEditor('container');

    layui.use(['upload'], function(){
        var upload = layui.upload;

        $.common.uploadFile(upload,'.upload')

    });

    $(function(){

    })
</script>
<?php $this->endBlock();?>

