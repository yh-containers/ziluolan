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
                <th width="40">ID</th>
                <th width="80">会员号</th>
                <th width="100">微信名称</th>
                <th width="80">等级</th>

                <th width="120">所属门店</th>
                <th width="120">推荐人</th>
                <th width="100">健康豆</th>
                <th width="100">钱包金额</th>
                <th width="100">消费金豆</th>
                <th width="100">团队业绩</th>
                <th width="100">团队提成</th>
                <th width="200">详细</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $vo){ ?>
                <tr <?= !$vo['open_id']?'style="color: #C11A1A"':'' ?> >
                    <th><?=$vo['id']?></th>
                    <th><?=$vo['number']?></th>
                    <th><?=$vo['username']?></th>
                    <th><?=\common\models\User::getPropInfo('fields_consume_type',$vo['consume_type'],'name')?></th>
                    <th><?=$vo['linkAdmin']['name']?></th>
                    <th><?=$vo['linkUserUp']['number']?></th>
                    <th><?=$vo['deposit_money']?></th>
                    <th><?=$vo['wallet']?></th>
                    <th><?=$vo['consum_wallet']?></th>
                    <th><?=$vo['team_wallet_full']?></th>
                    <th><?=$vo['team_wallet']?></th>
                    <th>
                        <a href="<?=\yii\helpers\Url::to(['detail','id'=>$vo['id']])?>" class="btn btn-xs btn-default">详细</a>
                        <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>" class="btn btn-xs btn-warning">编辑</a>
                        <a href="<?=\yii\helpers\Url::to(['order/index','user_id'=>$vo['id']])?>" class="btn btn-xs btn-primary">订单(<?=empty($vo['linkOrderCount'])?0:$vo['linkOrderCount']['order_num']?>)</a>
                        <a href="<?=\yii\helpers\Url::to(['finance/index','id'=>$vo['id']])?>" class="btn btn-xs btn-info">流水</a>
                        <a href="<?=\yii\helpers\Url::to(['del','id'=>$vo['id']])?>" class="btn btn-xs btn-danger">删除</a>
                    </th>
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
