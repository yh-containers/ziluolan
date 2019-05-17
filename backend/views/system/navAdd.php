<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','导航栏管理','导航栏操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">导航栏操作</h3>
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
                        <option value="0">一级导航</option>
                        <?php foreach ($nav as $vo){?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['pid']?'selected':''?>><?=$vo['name']?></option>
                            <?php foreach ($vo['linkNav'] as $item){?>
                                <option value="<?=$item['id']?>" <?=$item['id']==$model['pid']?'selected':''?>>&nbsp;&nbsp;&nbsp;&nbsp;┡━<?=$item['name']?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">导航栏名</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="15" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="导航栏名">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">导航栏链接</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="15" class="form-control" name="url" value="<?= $model['url'] ?>" placeholder="导航栏链接">
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
            <button type="button" class="btn btn-info col-sm-offset-2 col-sm-8 col-xs-12" id="submit"  onclick="$.common.formSubmit(null,<?=$model['id']?0:1?>)">保存</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>


<?php $this->endBlock(); ?>

<?php $this->beginBlock('script');?>
<script>
    $(function(){

    })
</script>
<?php $this->endBlock();?>

