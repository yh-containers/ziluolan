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

    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="5%">会员号</th>
                <th width="10%">微信名称</th>
                <th width="3%">等级</th>

                <th width="5%">所属门店</th>
                <th width="5%">推荐人</th>
                <th width="5%">节点推荐人</th>

                <th width="5%">预存押金</th>
                <th width="5%">健康豆</th>
                <th width="5%">钱包金额</th>
                <th width="5%">消费金豆</th>
                <th width="5%">团队业绩</th>
                <th width="5%">团队提成</th>


                <!-- <th width="5%">老客户</th> -->
                <th width="15%">详细</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $vo){ ?>
                <tr <?= !$vo['open_id']?'style="color: #C11A1A"':'' ?> >
                    <th><?=$vo['id']?></th>
                    <th><?=$vo['number']?></th>
                    <th><?=$vo['username']?></th>


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
