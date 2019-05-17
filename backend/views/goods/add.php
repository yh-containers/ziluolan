<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','商品管理','商品操作'],
    ];
?>
<?php $this->beginBlock('style'); ?>
<style>
    #goods-img .item{position: relative; display: inline-block}
    #goods-img .item i{right: 0px;position: absolute;z-index: 999;font-size: 24px;color: red;cursor: pointer}
    #add-spu-block input{width: 160px;display: inline-block}
    #add-spu-block .fa-close{color: red}
</style>
<?php $this->endBlock();?>

<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">商品操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品所属分类</label>

                <div class="col-sm-8">
                    <select class="form-control" name="n_id">
                        <option value="0">请选择商品分类</option>
                        <?php foreach ($nav as $vo){?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['n_id']?'selected':''?>><?=$vo['name']?></option>
                            <?php foreach ($vo['linkNavPage'] as $item){?>
                                <option value="<?=$item['id']?>" <?=$item['id']==$model['n_id']?'selected':''?>>&nbsp;&nbsp;&nbsp;&nbsp;┡━<?=$item['name']?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">名称</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="名称">
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品图片</label>

                <div class="col-sm-10 margin-bottom">
                    <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'goods'])?>',data:{'<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >上传文件</button>
                </div>
                <div class="col-sm-10 col-sm-offset-2" id="goods-img">
                    <?php $img = $model['image']?explode(',',$model['image']):[]; foreach ($img as $vo){?>
                        <div class="item">
                            <i class="fa fa-fw fa-close"></i>
                            <img src="<?=$vo?>" width="120" height="120"/>
                            <input type="hidden" name="image[]" value="<?=$vo?>"/>
                        </div>
                    <?php }?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">佣金模式</label>
                <div class="col-md-7">
                    <div class="radio">

                        <?php
                            $goods_mode = \common\models\Goods::getPropInfo('fields_mode');
                            if(!empty($goods_mode))
                                foreach ($goods_mode as $key=>$vo){
                        ?>
                        <label>
                            <input type="radio" name="mode"  value="<?=$key?>" <?= (empty($model)&& empty($key) )?'checked':($model['mode']==$key?'checked':'') ?>>
                            <?=$vo['name']?>
                        </label>
                        <?php }?>

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">销量</label>

                <div class="col-sm-8">
                    <input type="number" class="form-control" name="sold_num" value="<?= empty($model)?0:$model['sold_num']?>" placeholder="">
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
            <hr>
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-tab  layui-tab-brief" lay-filter="docDemoTabBrief">
                        <ul class="layui-tab-title">
                            <li class="layui-this">商品信息</li>
                            <li>商品规格</li>
                            <li>商品详细</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">商品价格:</label>

                                    <div class="col-sm-8">
                                        <?php if(empty($goods_sku_data)){?>
                                            <div class="form-group">
                                                <input type="number" class="form-control" name="sku_price[]" value="" placeholder="">
                                            </div>
                                        <?php }else{?>
                                            <?php foreach($goods_sku_data as $vo){?>
                                                <input type="hidden" name="sku_id[]" value="<?=$vo['id']?>"/>
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="sku_price[]" value="<?=$vo['price']?>" placeholder="">
                                                </div>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="form-group">
                                    <script id="attr" name="attr" type="text/plain"><?=$model['attr']?></script>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="form-group">
                                    <script id="container" name="content" type="text/plain"><?=$model['content']?></script>
                                </div>
                            </div>
                        </div>
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
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.config.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.all.js"></script>

<script>
    //规格
    var ue_attr = UE.getEditor('attr');
    //详细资料
    var ue = UE.getEditor('container');

    layui.use(['upload','element'], function(){
        var upload = layui.upload;
        var element = layui.element;
        $.common.uploadFile(upload,'#test1',(res,item)=>{
            $("#goods-img").append('<div class="item">\n' +
                '<i class="fa fa-fw fa-close"></i>\n' +
                '<img src="'+res.path+'" width="120" height="120"/>\n' +
                '<input type="hidden" name="image[]" value="'+res.path+'"/>'+
                '</div>')
        })

    });
    $(function(){

        $("#goods-img").on('click','.item i',function(){
            $(this).parent().remove()
        })
    })
</script>
<?php $this->endBlock();?>

