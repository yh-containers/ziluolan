<?php
$this->params=[
        'current_active'=>['user','user/index']
];
?>
<?php $this->beginBlock('style')?>

<style type="text/css">
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>
<div class="box box-info">

    <form class="form-horizontal"  id="form">
        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">
            <div class="col-sm-6">
                <div class="form-group">
                    <label  class="col-sm-2 control-label">会员号</label>

                    <div class="col-sm-10">
                        <input type="text" value="<?=$model['number']?>" readonly class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-2 control-label">微信名称</label>
                    <div class="col-sm-10">
                        <input type="text" name="username"  value="<?=$model['username']?>" class="form-control" placeholder="Email">
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-2 control-label">性别</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="sex"  value="1" <?= $model['sex']==1?'checked':'' ?>>
                                男
                            </label>
                            <label>
                                <input type="radio" name="sex" value="2" <?= $model['sex']==2?'checked':'' ?>>
                                女
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-2 control-label">会员等级</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="consume_type">
                            <?php foreach (\common\models\User::getPropInfo('fields_consume_type') as $key=>$vo){?>
                                <option value="<?=$key?>" <?=$key==$model['consume_type']?'selected':''?>><?=!empty($vo['name'])?$vo['name']:'暂无等级'?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-2 control-label">所属门店</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="admin_id">
                            <?php foreach ($store as $vo){?>
                                <option value="<?=$vo['id']?>" <?=$vo['id']==$model['admin_id']?'selected':''?>><?=$vo['name']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="col-sm-6">

                <div class="form-group">
                    <label  class="col-sm-2 control-label">钱包金额</label>

                    <div class="col-sm-10">
                        <input type="text" name="wallet" value="<?=$model['wallet']?>"  class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-2 control-label">健康豆</label>

                    <div class="col-sm-10">
                        <input type="text" name="deposit_money" value="<?=$model['deposit_money']?>"  class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-2 control-label">消费豆</label>
                    <div class="col-sm-10">
                        <input type="text" name="consum_wallet" value="<?=$model['consum_wallet']?>"  class="form-control" placeholder="0.00">
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






<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>



</script>
<?php $this->endBlock()?>
