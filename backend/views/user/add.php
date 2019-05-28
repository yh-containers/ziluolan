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
<div class="row">
    <div class="col-sm-6">
        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">会员号</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="150" class="form-control" name="username" value="<?= $model['username'] ?>" placeholder="名称">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">微信名称</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="150" class="form-control" name="username" value="<?= $model['username'] ?>" placeholder="名称">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>





<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>



</script>
<?php $this->endBlock()?>
