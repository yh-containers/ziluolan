<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','服务协议'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">

    <div class="box-header">
        <button type="button" class="btn btn-primary" id="submit"  onclick="$.common.formSubmit($('#form'),1)">保存</button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal"  action="<?= \yii\helpers\Url::to(['setting-save'])?>"  id="form">
        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="type" type="hidden" value="protocol">
        <div class="box-body">
            <script id="container" name="content" type="text/plain"><?=$content?></script>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        </div>
        <!-- /.box-footer -->
    </form>
</div>


<?php $this->endBlock(); ?>

<?php $this->beginBlock('script');?>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.config.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.all.js"></script>
<script>
    $(function(){
        var ue = UE.getEditor('container');

    })
</script>
<?php $this->endBlock();?>

