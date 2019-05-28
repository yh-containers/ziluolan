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
                <th width="60">ID</th>
                <th width="200">创建时间</th>
                <th width="200">订单号</th>
                <th width="120">会员名</th>
                <th width="120">会员号</th>
                <th width="120">所属门店</th>
                <th width="80">订单金额</th>
                <th width="80">支付金额</th>
                <th width="120">支付方式</th>
                <th width="120">发票类型</th>
                <th width="200">需求留言</th>
                <th width="100">状态</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($list as $vo){
                        $m_handle = $vo->getUserHandleAction('m_handle');
                ?>
                    <tr>
                        <td><?=$vo['id']?></td>
                        <td><?=$vo['create_time']?date('y-m-d H:i:s',$vo['create_time']):''?></td>
                        <td><?=$vo['no']?></td>
                        <td><?=$vo['linkUser']['username']?></td>
                        <td><?=$vo['linkUser']['number']?></td>
                        <td>所属门店</td>
                        <td><?=$vo['money']?></td>
                        <td><?=$vo['pay_money']?></td>
                        <td><?=!is_null($vo['pay_way'])?\common\models\Order::getPropInfo('fields_pay_way',$vo['pay_way'],'name'):''?></td>
                        <td><?=\common\models\Order::getPropInfo('fields_invoice',$vo['invoice_type'],'name')?></td>
                        <td><?=$vo['remark']?></td>
                        <td class="<?=$vo->getStepFlowInfo($vo['step_flow'],'field',null)?> <?=\common\models\Order::getPropInfo('fields_status',$vo['status'],'style')?>">
                            <?=$vo->getStepFlowInfo($vo['step_flow'])?>
                        </td>
                        <td>
                            <a class="btn btn-xs btn-info " href="<?=\yii\helpers\Url::to(['detail','id'=>$vo['id']])?>">订单详细</a>
                            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_SURE_PAY,$m_handle)){?>
                                <a href="javascript:;" class="btn  btn-xs btn-primary opt-order" data-href="<?=\yii\helpers\Url::to(['sure-pay'])?>" data-confirm_title="确定已收到付款?" data-req_data="{id:<?=$vo['id']?>}" >确认付款</a>
                            <?php }?>
                            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_DEL,$m_handle)){?>
                                <a href="javascript:;" class="btn  btn-xs btn-danger opt-order" data-href="<?=\yii\helpers\Url::to(['del'])?>" data-confirm_title="删除订单?" data-req_data="{id:<?=$vo['id']?>}" >删除订单</a>
                            <?php }?>
                            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_CANCEL,$m_handle)){?>
                                <a href="javascript:;" class="btn  btn-xs btn-warning opt-order" data-href="<?=\yii\helpers\Url::to(['cancel'])?>" data-confirm_title="确定取消订单?" data-req_data="{id:<?=$vo['id']?>}" >取消订单</a>
                            <?php }?>

                            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_SEND,$m_handle)){?>
                                <a href="javascript:;" class="btn  btn-xs btn-success opt-order send-order"  data-href="<?=\yii\helpers\Url::to(['send-down'])?>" data-confirm_title="确定已发货？" data-req_data="{id:<?=$vo['id']?>}" >发货</a>
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

<!--发货-->
<div id="send-order" style="display: none;">
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label">发货单号:</label>
        <div class="col-sm-8 margin-bottom">
            <input type="text" maxlength="100" class="form-control" name="no"  placeholder="发货单号">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label">物流公司:</label>
        <div class="col-sm-8 margin-bottom">
            <input type="text" maxlength="100" class="form-control" name="company"  placeholder="物流公司">
        </div>
    </div>
    <!-- <div class="form-group">
         <label for="inputPassword3" class="col-sm-3 control-label">运费:</label>
         <div class="col-sm-8 margin-bottom">
             <input type="number"  class="form-control" name="money"  placeholder="0.00">
         </div>
     </div>-->
</div>




<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>


    $(function(){
        layui.use(['layer'], function(){
            var layer = layui.layer;



        });



        $(".opt-order").click(function(){
            var href = $(this).data('href')
            var req_data = $(this).data('req_data')
            var confirm_title = $(this).data('confirm_title')
            req_data = eval('('+req_data+')')

            //订单发货
            if($(this).hasClass('send-order')){
                layer.open({
                    type:1
                    ,title:'填写发货信息'
                    ,btn: ['确认', '取消']
                    ,area:['400px','300px']
                    ,content:$("#send-order")
                    ,yes: function(index, layero){
                        //按钮【按钮一】的回调
                        req_data.logistics={}
                        req_data['logistics']['no']=$("#send-order input[name='no']").val()
                        req_data['logistics']['company']=$("#send-order input[name='company']").val()
                        req_data['logistics']['money']=$("#send-order input[name='money']").val()
                        //请求数据
                        reqInfo(href,req_data,confirm_title)
                    }
                })
            }else{
                //请求数据
                reqInfo(href,req_data,confirm_title)
            }
        })


        function reqInfo(href,req_data,confirm_title){
            layer.confirm(confirm_title,function(){
                var index = layer.load(3)
                $.get(href,req_data,function(result){
                    layer.close(index)
                    layer.msg(result.msg)
                    if(result.code==1){
                        setTimeout(function(){location.reload()},1000)
                    }
                })
            })
        }
    })
</script>
<?php $this->endBlock()?>
