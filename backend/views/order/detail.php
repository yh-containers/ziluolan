<?php
$this->params=[
        'current_active'=>['user','user/index']
];
?>
<?php $this->beginBlock('style')?>

<style type="text/css">
    .layui-table[lay-size=lg] td,.layui-table[lay-size=lg] th{padding: 12px 10px; }
    .box-body>a{margin: 5px 2px;}

</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="col-sm-9">
    <div class="box">
        <div class="box-header with-border">
            <h3>订单基本信息</h3>
        </div>
        <div class="box-body">
            <table class="layui-table"  lay-size="lg">
                <colgroup>
                    <col width="170">
                    <col width="190">
                    <col width="170">
                    <col width="190">
                    <col width="170">
                    <col width="190">
                    <col width="170">
                    <col width="190">
                </colgroup>

                <tbody>
                <tr>
                    <td>订单号</td>
                    <td><?=$model['no']?></td>
                    <td>用户名</td>
                    <td><?=$model['linkUser']['username']?></td>
                    <td>所属门店</td>
                    <td class="text-green"><?=$model['linkStore']['name']?></td>
                    <td>状态</td>
                    <td>
                        <span class="btn btn-success <?=empty($model)?'': $model->getStepFlowInfo($model['step_flow'],'field',null)?> <?=\common\models\Order::getPropInfo('fields_status',$model['status'],'style')?>">
                            <?=empty($model)?:$model->getStepFlowInfo($model['step_flow'])?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>订单金额</td>
                    <td><?=$model['money']?></td>
                    <td>支付</td>
                    <td><?=$model['pay_money']?></td>
                    <td>总优惠</td>
                    <td><?=$model['dis_money']?></td>
                    <td>支付方式</td>
                    <td class="text-red"><?=\common\models\Order::getPropInfo('fields_pay_way',$model['pay_way'],'name')?></td>
                </tr>
                <tr>
                    <td>金豆抵扣数量</td>
                    <td><?=$model['use_inv_pear']?></td>
                    <td>金豆抵扣金额</td>
                    <td><?=$model['inv_pear_dis_money']?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>

                <tr>
                    <td>创建时间</td>
                    <td><?=$model['createTime']?></td>
                    <td>支付时间</td>
                    <td><?=$model['pay_time']?date('Y-m-d H:i:s',$model['pay_time']):''?></td>
                    <td></td>
                    <td></td>
                    <td>收货方式</td>
                    <td class="text-light-blue"><?=\common\models\Order::getPropInfo('fields_rec_mode',$model['rec_mode'],'name')?></td>



                </tr>
                <tr>
                    <td>发货时间</td>
                    <td><?=$model['send_end_time']?date('Y-m-d H:i:s',$model['send_end_time']):''?></td>
                    <td>收货时间</td>
                    <td><?=$model['receive_end_time']?date('Y-m-d H:i:s',$model['receive_end_time']):''?></td>
                    <td>完成时间</td>
                    <td><?=$model['complete_time']?date('Y-m-d H:i:s',$model['complete_time']):''?></td>
                    <td>发票模式</td>
                    <td class="text-green"><?=\common\models\Order::getPropInfo('fields_invoice',$model['invoice_type'],'name')?></td>

                </tr>
                <?php
                    $invoice_data = empty($model['invoice_content'])?[]:json_decode($model['invoice_content'],true);
                    if(!empty($invoice_data)){
                ?>
                <tr style="background: #f5f5f5;font-weight: bold">
                    <td colspan="8">发票信息</td>
                </tr>
                <?php
                    for($i=0;$i<count($invoice_data);$i+=4){
                ?>
                <tr>
                    <td><?=isset($invoice_data[$i])?$invoice_data[$i]['name']:''?></td>
                    <td><?=isset($invoice_data[$i])?$invoice_data[$i]['value']:''?></td>
                    <td><?=isset($invoice_data[$i+1])?$invoice_data[$i+1]['name']:''?></td>
                    <td><?=isset($invoice_data[$i+1])?$invoice_data[$i+1]['value']:''?></td>
                    <td><?=isset($invoice_data[$i+2])?$invoice_data[$i+2]['name']:''?></td>
                    <td><?=isset($invoice_data[$i+2])?$invoice_data[$i+2]['value']:''?></td>
                    <td><?=isset($invoice_data[$i+3])?$invoice_data[$i+2]['name']:''?></td>
                    <td><?=isset($invoice_data[$i+3])?$invoice_data[$i+3]['value']:''?></td>

                </tr>
                <?php } }?>
                <tr style="background: #f5f5f5;font-weight: bold">
                    <td colspan="8">客户留言</td>
                </tr>
                <tr>
                    <td colspan="8">
                        <?=$model['remark']?$model['remark']:'暂无内容'?>
                    </td>

                </tr>
                <tr style="background: #f5f5f5;font-weight: bold">
                    <td colspan="8">收货地址</td>
                </tr>
                <tr>
                    <td>收货人:</td>
                    <td><?=$model['linkAddr']['username']?></td>
                    <td>手机号码</td>
                    <td><?=$model['linkAddr']['phone']?></td>
                    <td>地址</td>
                    <td colspan="3"><?=$model['linkAddr']['addr'].'  '.$model['linkAddr']['addr_extra']?></td>
                </tr>
                <tr style="background: #f5f5f5; font-weight: bold">
                    <td colspan="8">发货信息</td>
                </tr>
                <tr>
                    <td>物流公司:</td>
                    <td><?=$model['linkLogistics']['company']?></td>
                    <td>物流单号</td>
                    <td><?=$model['linkLogistics']['no']?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                </tbody>
            </table>
        </div>

    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3>订单商品</h3>
        </div>
        <div class="box-body">
            <table class="layui-table"  lay-size="lg">
                <thead>
                <tr>
                    <th width="250">商品名称</th>
                    <th width="200">商品sku组合名</th>
                    <th width="120">商品价格</th>
                    <th width="80">购买数量</th>
                    <th width="80">支付单价</th>
                    <th width="80">支付金额</th>
                    <th width="80">提成模式</th>
                </tr>
                </thead>

                <tbody>
                <?php if(!empty($model)) foreach($model['linkGoods'] as $vo){?>
                    <tr>
                        <td><?=$vo['name']?></td>
                        <td><?=$vo['sku_name']?></td>
                        <td><?=$vo['price']?></td>
                        <td><?=$vo['num']?></td>
                        <td><?=$vo['pay_price']?></td>
                        <td><?=$vo['pay_money']?></td>
                        <td><?=\common\models\Goods::getPropInfo('fields_mode',$vo['g_mode'],'name')?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3>提成信息</h3>
        </div>
        <div class="box-body">
            <table class="layui-table"  lay-size="lg">
                <thead>
                <tr>
                    <th width="150">用户名</th>
                    <th width="80">提成金额</th>
                    <th width="250">说明</th>
                    <th width="120">提成时间</th>
                </tr>
                </thead>

                <tbody>
                <?php if(!empty($model)) foreach($model['linkOrderComLog'] as $vo){?>
                    <tr>
                        <td><?=$vo['linkUser']['username']?></td>
                        <td><?=$vo['quota']?></td>
                        <td><?=$vo['intro']?></td>
                        <td><?=$vo['create_time']?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>

        </div>

    </div>
</div>

<div class="col-sm-3">
    <div class="box">
        <div class="box-header with-border">
            <h3>订单操作</h3>
        </div>
        <div class="box-body">
            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_SURE_PAY,$m_handle)){?>
                <a href="javascript:;" class="btn btn-primary opt-order" data-href="<?=\yii\helpers\Url::to(['sure-pay'])?>" data-confirm_title="确定已收到付款?" data-req_data="{id:<?=$model['id']?>}" >确认付款</a>
            <?php }?>
            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_DEL,$m_handle)){?>
                <a href="javascript:;" class="btn btn-danger opt-order" data-href="<?=\yii\helpers\Url::to(['del'])?>" data-confirm_title="删除订单?" data-req_data="{id:<?=$model['id']?>}" >删除订单</a>
            <?php }?>
            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_CANCEL,$m_handle)){?>
                <a href="javascript:;" class="btn btn-warning opt-order" data-href="<?=\yii\helpers\Url::to(['cancel'])?>" data-confirm_title="确定取消订单?" data-req_data="{id:<?=$model['id']?>}" >取消订单</a>
            <?php }?>

            <?php if(in_array(\common\models\Order::M_ORDER_HANDLE_SEND,$m_handle)){?>
                <a href="javascript:;" class="btn btn-success opt-order send-order"  data-href="<?=\yii\helpers\Url::to(['send-down'])?>" data-confirm_title="确定已发货？" data-req_data="{id:<?=$model['id']?>}" >发货</a>
            <?php }?>
        </div>

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
