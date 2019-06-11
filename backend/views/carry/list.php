<?php

    $this->params = [
            'crumb'          => ['系统设置','提现管理','提现列表'],
    ];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="layer-photos-demo">
                <thead>
                <tr>
                    <th width="60">ID</th>
                    <th width="120">申请时间</th>
                    <th width="120">会员名</th>
                    <th width="80">提现金额</th>
                    <th width="80">手续费</th>
                    <th width="80">实际提现金额</th>
                    <th width="80">开户银行</th>
                    <th width="200">卡号</th>
                    <th width="80">开户人</th>
                    <th width="120">手机</th>
                    <th width="100">状态</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$vo['id']?></td>
                        <td><?=$vo['create_time']?> </td>
                        <td><?=$vo['linkUser']['username']?>(<?=$vo['linkUser']['number']?>)</td>
                        <td><?=$vo['in_money']?></td>
                        <td><?=$vo['com_money']?></td>
                        <td><?=$vo['out_money']?></td>
                        <td><?=$vo['bank_name']?></td>
                        <td><?=$vo['bank_number']?></td>
                        <td><?=$vo['bank_username']?></td>
                        <td><?=$vo['bank_phone']?></td>
                        <td class="<?=empty($vo['status'])?'':($vo['status']==1?'text-green':'text-red')?>"><?=\common\models\UserWithdraw::getPropInfo('fields_status',$vo['status'])?></td>
                        <td>
                            <?php if(empty($vo['status'])){?>
                            <a class="layui-btn layui-btn-success layui-btn-sm auth" href="javascript:;" data-id="<?=$vo['id']?>" class="ml-5">  审核</a>
                            <?php }?>
                        </td>
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
<?php $this->beginBlock('script');?>
<script>
    layui.use(['layer'], function(){
        var layer = layui.layer;

        //审核
        $(".auth").click(function(){
            var id = $(this).data('id');
            layer.confirm('是否通过?',{
                btn: ['通过', '拒绝','取消']
                ,yes:function(index, layero){
                    handle_auth(id,1)
                }
                ,btn2:function(index,layero){
                    handle_auth(id,2)
                }
            })
        })



        function handle_auth(id,state) {
           sendAjax({
                url:"<?=\yii\helpers\Url::to(['handle-auth'])?>",
                data:{id:id,state:state},
            })
        }
    });


</script>
<?php $this->endBlock();?>
