<?php
$this->params=[
        'current_active'=>['user','user/index']
];
?>
<?php $this->beginBlock('style')?>

<style type="text/css">
    .bootgrid-table td{text-overflow:none; white-space:inherit;} #foonav{display:none !important;} #foonav2{display:none !important;} .posF{display: none;height: 100%;left: 0; position: fixed; top: 0;width: 100%; z-index: 99999;}
    .posF .bg{background: #000 none repeat scroll 0 0; height: 100%; left: 0; opacity: 0.35; position: absolute; top: 0; width: 100%; z-index: -1;}
    .posF .box500{background: #fff none repeat scroll 0 0; border-radius: 6px; margin: 12% auto 0; width: 500px;}
    .posF .box500 .hd{border-bottom: 2px solid #dfdfdf; position: relative;}
    .posF .box500 .hd p{color: #535353; font-size: 20px; line-height: 50px; margin: 0; text-indent: 2em;}
    .posF .box500 .hd .off{background: rgba(0, 0, 0, 0) url("<?=\Yii::getAlias('@assets')?>/assets/images/icon_index.png") no-repeat scroll -230px -350px; cursor: pointer; display: block; height: 33px; margin-top: -16.5px; position: absolute; right: 15px; top: 50%; width: 35px;}
    .posF .bd{padding: 30px;}
    .posF .rope{margin: 0 25px 10px; padding: 0; text-align: center;}
    .posF .rope span{color: #535353; display: inline-block; width: 70px;}
    .posF .rope select{border: 1px solid #e5e5e5; color: #333; height: 36px; outline: medium none; width: 310px;}
    .posF .rope input{border: 1px solid #e5e5e5; color: #333; height: 34px; line-height: 34px; outline: medium none; padding: 0 7px; width: 296px;}
    .posF .rope button.off{background-color: #b5b5b5; color: #fff; margin-left: 0;}
    .posF .rope .submit{background: #f10215 none repeat scroll 0 0; color: #fff;}
    .posF .rope a{border: 0 none; border-radius: 4px; cursor: pointer; display: inline-block; font-family: "Microsoft Yahei"; font-size: 14px; height: 36px; line-height: 36px; margin-left: 23px; outline: medium none; padding: 0 15px; width: 105px;}
    .show1{display: block;}
    .page {text-align: right;padding: 10px 0;  width: 100%; margin: 0 auto;}
    .page a {display: inline-block;padding: 5px 15px;color:#333;font-size:15px; border-radius: 4px;border: 1px solid #ccc;margin-left: 5px;}
    .page a:hover,.page .on {background: #00a3ff;color:#fff}
    #example1_filter{margin-left:30px}
    #example1_filter .so {width: 80px;height: 30px;background: #1485BD;color: #fff;display: block;float: right;text-align: center;line-height: 30px;margin-left: 20px;border-radius: 3px;overflow:hidden;}
    #example1_filter .so:hover {background:#1F76A0;}
    select{height: 30px; width: 200px;} select option{font-weight: normal; display: block; white-space: pre; min-height: 1.2em; padding: 0px 2px 1px;}

</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">用户:<?=$model['username']?> (会员号:<?=$model['number']?>)订单列表:</h3>
        <a href='<?=\yii\helpers\Url::to(['index'])?>' class="btn btn-primary" onclick="">返回</a>
        <span style="margin-left:15px">总订单金额：<?=$prices?></span>
    </div>
    <div class="box-body">
        <form action="" method="GET" class="submit_form">
            <input type="hidden" name="id" value="<?=$model['id']?>"/>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">订单列表</a></li>



                <div id="example1_filter" class="dataTables_filter" style="display:inline-block;margin-left:38px">
                    <label>筛选：
                        <select class="" name='state' onchange="ongetdengji()">
                            <option value="" <?php if($state==''){echo 'selected=""';} ?> >全部</option>
                            <option value="9" <?php if($state==9){echo 'selected=""';} ?> >待支付</option>
                            <option value="1" <?php if($state==1){echo 'selected=""';} ?> >已支付</option>
                            <option value="2" <?php if($state==2){echo 'selected=""';} ?> >完成</option>
                            <option value="4" <?php if($state==4){echo 'selected=""';} ?> >订单取消申请</option>
                            <option value="5" <?php if($state==5){echo 'selected=""';} ?> >订单取消成功</option>
                        </select>
                </div>
                <div id="example1_filter" class="dataTables_filter" style="float:right;display:inline-block;margin-left:38px">
                    <label style="display: flex;">
                        <span style="width: 120px">总搜索：</span><input type="text" name="sou" value="<?php echo $sou;?>" class="form-control input-sm" placeholder="" aria-controls="example1" autocomplete="off">
                        <a href="javascript:;" onclick="ongetdengji()" style="float: right" class="so">确定</a>
                    </label>
                </div>
            </ul>
        </form>
        <script>
            function ongetdengji(){
                $(".submit_form").submit();
            }
        </script>
        <table id="example1" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th width="6%">ID</th>
                <th>时间</th>
                <th>订单号</th>
                <th>会员名</th>
                <th>会员号</th>
                <th>下单产品</th>
                <th>订单金额</th>
                <th>支付方式</th>
                <th>发票类型</th>
                <th>留言需求</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $vo){?>
                <tr>
                    <td><?=$vo['id']?></td>
                    <td><?=$vo['addtime']?date('Y-m-d H:i:s',$vo['addtime']):''?></td>
                    <td><?=$vo['sn']?></td>
                    <td><?=$vo['linkMember']['username']?></td>
                    <td><?=$vo['linkMember']['number']?></td>
                    <td>
                        <button class="btn btn-primary btn-xs look-goods-cart"  data-id="<?=$vo['id']?>" data-goods_info='<?=json_encode($vo['cart_base_info'],JSON_UNESCAPED_UNICODE)?>'>查看</button>
                    </td>
                    <td><?=$vo['prices']?></td>
                    <td><?=$vo['payment']?\common\models\OrderList::getPayment($vo['payment']):''?></td>
                    <td><a href="javascript:;" <?php if($vo['fapiao']==2){?>onclick="chakp('<?=$vo['companyname']?>','<?=$vo['taxpayernumber']?>','<?=$vo['registeaddress']?>','<?=$vo['registetelephone']?>','<?=$vo['bank']?>','<?=$vo['bankaccount']?>')"<?php }?>><?=\common\models\OrderList::getFapiao($vo['fapiao'])?></a></td>
                    <td><?=$vo['message']?></td>
                    <td>
                        <?php if($vo['state']==0){?>
                            <button class="btn btn-primary btn-xs" style="background:#a94442" onclick="$.common.confirm('<?= \yii\helpers\Url::to(['order/sure-pay','id'=>$vo['id']])?>',{'<?=\Yii::$app->request->csrfParam?>':'<?= Yii::$app->request->csrfToken ?>'},'确定 ID:<?=$vo['id']?>完成支付吗？')">待支付</button>
                        <?php }?>
                        <?php if($vo['state']==1){?>
                            <button class="btn btn-primary btn-xs" style="background:#00a65a" onclick="dianji_fh(this,<?=$vo['id']?>)">已支付</button>
                        <?php }?>
                        <?php if($vo['state']==2){?>
                            <button class="btn btn-primary btn-xs" style="background:#444" >已完成</button>
                        <?php }?>
                        <?php if($vo['state']==3){?>
                            <button class="btn btn-primary btn-xs" style="background:#444" >已完成</button>
                        <?php }?>
                        <?php if($vo['state']==4){?>
                            <a class="btn btn-primary btn-xs" style="background:#a94442" >订单取消申请</a>
                            <a class="btn btn-primary btn-xs" style="background:#dd4b39"  onclick="$.common.confirm('<?= \yii\helpers\Url::to(['order/auth','id'=>$vo['id']])?>',{},'确定审核通过退回款项？')" >审核</a>
                        <?php }?>
                        <?php if($vo['state']==5){?>
                            <a class="btn btn-primary btn-xs" style="background:#ccc" >订单取消成功</a>
                        <?php }?>
                    </td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="<?=\yii\helpers\Url::to(['order/detail','id'=>$vo['id']])?>">订单详细</a>
                        <button class="btn btn-danger btn-xs" onclick="$.common.del('<?= \yii\helpers\Url::to(['order/del','id'=>$vo['id']])?>','删除')">删除</button>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>

    </div>
    <div class="box-footer clearfix" style="text-align: right">
        <?= \yii\widgets\LinkPager::widget(['pagination'=>$pagination])?>
    </div>
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    商品信息
                </h4>
            </div>
            <div class="modal-body">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!--发货-->
<div class="posF" id="expre" >
    <div class="bg"></div>
    <div class="box500">
        <div class="hd posR">
            <p>发货信息</p>
            <span class="off posA" onclick="t_fh_off(this)">&nbsp;</span>
        </div>
        <div class="bd">
            <form action="<?=\yii\helpers\Url::to(['order/send'])?>" method="post" id="form-send">
                <input name="<?=\Yii::$app->request->csrfParam?>" type="hidden"  value="<?= Yii::$app->request->csrfToken ?>">
                <div class="rope">
                    <span>快递名称</span>
                    <input type="text" name="sp_name" id="sp_name" class="shipping_num" placeholder="快递名称">
                    <input type="hidden" name="send_id" class="send_ids" id="send_ids" value="">
                </div>
                <div class="rope">
                    <span>快递单号</span>
                    <input type="text" name="sp_on" id="sp_on" class="shipping_num" placeholder="请输入快递单号">
                </div>
                <div class="rope">
                    <a  class="submit" onclick="$.common.formSubmit($('#form-send'),1)">确定发货</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="posF" id="expres" >
    <div class="bg"></div>
    <div class="box500">
        <div class="hd posR">
            <p>发票信息</p>
            <span class="off posA" onclick="t_fh_offs(this)">&nbsp;</span>
        </div>
        <div class="bd">
            <div class="rope" style="margin-bottom:10px; color:red">
                单位名称:<label class="companyname"></label><br/>
                纳税人识别号:<label class="taxpayernumber"></label><br/>
                注册地址:<label class="registeaddress"></label><br/>
                注册电话:<label class="registetelephone"></label><br/>
                开户银行:<label class="bank"></label><br/>
                银行账号:<label class="bankaccount"></label>
            </div>
            <!-- <div class="rope">
              <input type="hidden" value="" id="senval">
              <a onclick="sen()" style="margin-bottom:10px; background:#00a65a" class="submit">确定</a>
              <a onclick="t_fh_offs(this)" class="submit">取消</a>
            </div>  -->
        </div>
    </div>
</div>


<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>
    //审核
    function shenhe(id){
        var gnl=confirm("确定审核通过退回款项？");
        if (gnl!=true){
            return false;
        };

        $.post('/Admin/Orderlist/shenhe', {id : id}, function (re){
            if(re == 1){
                window.location.reload();
            }else{
                window.location.reload();
            }
        })
    }
    function chakp(companyname,taxpayernumber,registeaddress,registetelephone,bank,bankaccount){
        $(".companyname").html(companyname);
        $(".taxpayernumber").html(taxpayernumber);
        $(".registeaddress").html(registeaddress);
        $(".registetelephone").html(registetelephone);
        $(".bank").html(bank);
        $(".bankaccount").html(bankaccount);
        $("#expres").addClass('show1');
    }
    function t_fh_offs(_this){
        $("#expres").removeClass('show1');
    }
    //打开支付确定
    function dianji_fh(_this,id){
        $("#expre .send_ids").val(id);
        $("#expre").addClass('show1');
    }
    //关闭 支付确定
    function t_fh_off(_this){
        $("#expre").removeClass("show1");
    }

    //确定发货
    function sends(id,_this){
        var id=$('#send_ids').val();
        var val=$('#sp_name').val();
        var on=$('#sp_on').val();
        if(!val){
            alert("快递名称不能为空");
            return false;
        }
        if(!on){
            alert("快递单号不能为空");
            return false;
        }
        $.post('/Admin/Orderlist/tshop', {id : id , val:val ,on:on}, function (re){
            if(re == 1){
                $("#expre").removeClass("show1");
                window.location.reload();
            }else{
                $("#expre").removeClass("show1");
                window.location.reload();
            }
        })
    }

    $(".look-goods-cart").click(function(){
        var goods_info = $(this).data('goods_info')
        var html = '';
        var goods_url = '<?=\yii\helpers\Url::to('/view/')?>';
        goods_info.map(function(item,index){
            html +='产品名:   <a href="'+goods_url+item.goods_id+'" target="_blank ">'+item.goods_name+(item.shop_type?'|'+item.shop_type:'')+'</a> <br/>';
            html +='数目/单价:<span style="color:blue">'+item.price+'元   '+item.num*item.guige+'件</span><br/>';
        })
        $("#myModal .modal-body").html(html);

        $('#myModal').modal('show');
    })



    function partner(id,partner){
        $("#id").val(id);
        $("#sp_name").val(partner);
        $("#expre").addClass('show1');
    }
    function t_fh_off(_this){
        $("#expre").removeClass('show1');
    }
    //确定付款
    function qdingzf(id){
        if(window.confirm('确定 ID:'+id+'完成支付吗？')){
            $.post('<?=\yii\helpers\Url::to(['order/sure-pay'])?>', {id : id }, function (re){
                if(re.state == 1){
                    window.location.reload();
                }else{
                    alert('确定支付失败，请稍后再试');
                }
                return true;
            });

        }
    }
</script>
<?php $this->endBlock()?>
