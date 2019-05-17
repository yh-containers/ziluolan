<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','新闻管理','文章操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">文章操作</h3>
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
                    <select class="form-control" name="cid">
                        <option value="">顶级栏目</option>
                        <?php foreach ($nav as $vo){?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['cid']?'selected':''?>><?=$vo['name']?></option>
                            <?php foreach ($vo['linkNavPage'] as $item){?>
                                <option value="<?=$item['id']?>" <?=$item['id']==$model['cid']?'selected':''?>>&nbsp;&nbsp;&nbsp;&nbsp;┡━<?=$item['name']?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">标题</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="title" value="<?= $model['title'] ?>" placeholder="标题">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">相关产品ID</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="relation_id" value="<?= $model['relation_id'] ?>" placeholder="相关产品ID">
                    <span class="help-block">多个使用英文逗号分割 例如:191,192,193</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">相关标签</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="label" value="<?= $model['label'] ?>" placeholder="相关标签">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文章日期</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control lay-date" name="addtime" value="<?= $model['addtime'] ? $model['addtime'] : date('Y-m-d H:i:s') ?>" placeholder="文章日期">
                </div>
            </div>



            <div class="form-group">
                <label class="col-md-2 control-label">图片：</label>
                <div class="col-md-7">
                    <input type="hidden" name="image" value="<?=$model['image']?>"/>
                    <button class="layui-btn upload"  type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'article'])?>',data:{ '<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >选择图片</button>
                    <img src="<?= $model['image'] ?>" alt="图片" class="radius" width="80" height="80">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文章来源</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="from" value="<?= $model['from'] ?>" placeholder="文章来源">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">关键字</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="key" value="<?= $model['key']?>" placeholder="关键字">
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文章描述</label>

                <div class="col-sm-8">
                    <textarea type="text" maxlength="255" class="form-control" name="desc" rows="5"  placeholder="文章描述"><?= $model['desc'] ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">访问次数</label>

                <div class="col-sm-8">
                    <input type="number" maxlength="150" class="form-control" name="visit" value="<?= $model['visit']?$model['visit']:0 ?>" placeholder="访问次数">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">是否推荐</label>
                <div class="col-sm-8">
                    <div class="radio">
                        <label>
                            <input type="radio" name="is_up"  value="1" <?=$model['is_up']==1?'checked':''?> >
                            是
                        </label>
                        <label>
                            <input type="radio" name="is_up" value="0"  <?= (empty($model) ||empty($model['is_up'])) ?'checked':''?> >
                            否
                        </label>
                    </div>
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
                <label for="inputPassword3" class="col-sm-2 control-label">文章内容</label>

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

    layui.use(['upload','laydate'], function(){
        var upload = layui.upload;
        var laydate = layui.laydate;
        laydate.render({
            elem: '.lay-date' //指定元素
            ,type:'datetime'
        });
        $.common.uploadFile(upload,'.upload')

    });

    $(function(){

    })
</script>
<?php $this->endBlock();?>

