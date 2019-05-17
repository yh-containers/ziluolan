<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','管理员管理','角色操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">角色操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-header">
            <button type="button" class="btn btn-info  " id="submit"  onclick="$.common.formSubmit()">保存</button>
        </div>
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">角色等级</label>

                <div class="col-sm-10">
                    <select name="pid" class="form-control">
                        <option value="0">一级角色</option>
                        <?php foreach($top_role as $vo) {?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['pid']?'selected':''?>><?=$vo['name']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">名称</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="25" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="角色名">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="sort" value="<?= $model['sort']?$model['sort']:100 ?>" placeholder="">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">状态</label>

                <div class="col-sm-10">
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
                <label for="inputPassword3" class="col-sm-1 control-label">权限</label>

                <div class="col-sm-10">
                    <table class="table table-bordered">

                        <thead>
                        <tr>
                            <th width="120">顶级栏目</th>
                            <th>栏目节点</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach($node as $vo){?>
                            <tr>
                                <td rowspan="<?=count($vo['linkNode'])+1?>">
                                    <label>
                                        <input type="checkbox" name="node[]"  value="<?=$vo['uri']?>" <?=stripos($model['node'],$vo['uri'])!==false?'checked':''?> >
                                        <?=$vo['name']?>
                                    </label>
                                </td>
                                <td >--</td>
                            </tr>
                            <?php foreach($vo['linkNode'] as $one){?>
                                <tr>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="node[]"  value="<?=$one['uri']?>" <?=stripos($model['node'],$one['uri'])!==false?'checked':''?> >
                                            <?=$one['name']?>
                                        </label>
                                    <?php if(empty($one['uri'])){?>
                                        <table class="table table-bordered">
                                            <tbody>
                                            <?php foreach($one['linkNode'] as $two){?>
                                                <tr>
                                                    <td width="180">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label>
                                                            <input type="checkbox" name="node[]"  value="<?=$two['uri']?>" <?=stripos($model['node'],$two['uri'])!==false?'checked':''?> >
                                                            <?=$two['name']?>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <?php foreach($two['linkNode'] as $three){?>
                                                            <label>
                                                                <input type="checkbox" name="node[]"  value="<?=$three['uri']?>"  <?=stripos($model['node'],$three['uri'])!==false?'checked':''?> >
                                                                <?=$three['name']?>
                                                            </label>
                                                        <?php }?>
                                                    </td>
                                                </tr>

                                            <?php }?>


                                            </tbody>
                                        </table>
                                    <?php }else{?>
                                            <?php foreach($one['linkNode'] as $two){?>
                                            <label>
                                                <input type="checkbox" name="node[]"  value="<?=$two['uri']?>" <?=stripos($model['node'],$two['uri'])!==false?'checked':''?> >
                                                <?=$two['name']?>
                                            </label>
                                            <?php }?>
                                    <?php }?>

                                    </td>
                                </tr>
                            <?php }?>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </form>
</div>


<?php $this->endBlock(); ?>

<?php $this->beginBlock('script');?>
<script>
    $(function(){
        $("input[type='checkbox']").change(function () {
            var is_checked = $(this).prop('checked');
            var rowspan = $(this).parent().parent().prop('rowspan');
            if(rowspan>1){
                var index = $(this).parents('tr').index()
                $(this).parents('tr').nextAll().each(function(){
                    if($(this).index()>index && $(this).index()<(index+rowspan)){
                        $(this).find("input[type='checkbox']").prop('checked',is_checked?true:false  )
                    }
                })
            }else{
                var label_len = $(this).parent().parent().find('label').length;
                if(label_len>1){
                    $(this).parent().nextAll().find("input[type='checkbox']").prop('checked',is_checked?true:false  )
                }else{
                    $(this).parent().parent().next().find("input[type='checkbox']").prop('checked',is_checked)
                }
            }

        })
    })
</script>
<?php $this->endBlock();?>

