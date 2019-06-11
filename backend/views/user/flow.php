<?php
$this->params=[
        'current_active'=>['user','user/index']
];
?>
<?php $this->beginBlock('style')?>

<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>



<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">用户:<?=$model_user['username']?> 会员号:<?=$model_user['number']?></h3>
        <a href="javascript:history.back();" class="btn btn-primary" onclick="">返回</a>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th width="80">时间</th>
                <th width="120">类型</th>
                <th width="80">资金</th>
                <th width="250">说明</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $vo){ ?>
                <tr>
                    <th><?=$vo['create_time']?></th>
                    <th><?=\common\models\UserLog::getPropInfo('fields_type',$vo['type'],'name')?></th>
                    <th><?=$vo['quota']?></th>
                    <th><?=$vo['intro']?></th>
                </tr>
            <?php }?>
            </tbody>
        </table>

    </div>

    <!-- /.box-body -->
    <div class="box-footer clearfix">
        <?= \yii\widgets\LinkPager::widget(['pagination'=>$pagination])?>
    </div>
</div>




<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>
    //修改推荐人
    function getsh(id,name,type){
        $("#send_ids").val(id);
        $("#sp_name").val(name);
        $("#type").val(type);
        $("#expre").addClass('show1');
    }
    function partner(id,partner){
        $("#id").val(id);
        $("#sp_name").val(partner);
        $("#expre").addClass('show1');
    }
    function t_fh_off(_this){
        $("#expre").removeClass('show1');
    }

</script>
<?php $this->endBlock()?>
