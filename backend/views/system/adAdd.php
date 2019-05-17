<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','广告操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">广告操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">名称</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="名称">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">链接地址</label>
                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="url" value="<?= $model['url'] ?>" placeholder="链接地址">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">图片：</label>
                <div class="col-md-7">
                    <input type="hidden" name="image" value="<?=$model['image']?>"/>
                    <button class="layui-btn upload"  type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'ad'])?>',data:{ '<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >选择图片</button>
                    <img src="<?= $model['image'] ?>" alt="图片" class="radius" width="80" height="80">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">广告类型</label>
                <div class="col-md-7">
                    <div class="radio">
                        <label>
                            <input type="radio" name="status"  value="1" <?= $model['status']!=2?'checked':'' ?>>
                            类型1
                        </label>
                        <label>
                            <input type="radio" name="status" value="2" <?= $model['status']==2?'checked':'' ?>>
                            类型2
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">标题</label>
                <div class="col-sm-8">
                    <input type="text" maxlength="255" class="form-control" name="title" value="<?= $model['title'] ?>" placeholder="标题">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">描述</label>
                <div class="col-sm-8">
                    <input type="text" maxlength="255" class="form-control" name="desc" value="<?= $model['desc'] ?>" placeholder="描述">
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
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="button" class="btn btn-info col-sm-offset-2 col-sm-8 col-xs-12" id="submit"  onclick="$.common.formSubmit()">保存</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>


<?php $this->endBlock(); ?>

<?php $this->beginBlock('script');?>
<script>
    layui.use(['upload'], function(){
        var upload = layui.upload;

        $.common.uploadFile(upload,'.upload')

    });
    $(function(){

    })
</script>
<?php $this->endBlock();?>

