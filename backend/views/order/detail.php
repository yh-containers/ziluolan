<?php
$this->params=[
        'current_active'=>['user','user/index']
];
?>
<?php $this->beginBlock('style')?>

<style type="text/css">
    #foonav{ display:none !important;}
    #foonav2{ display:none !important;}

</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>


<!-- general form elements -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">订单详细</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab">订单详细</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="home">
                <div class="box-body">
                    <!-- 中文网站信息 -->
                    <form class="form-horizontal" action=""  id="myform">
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-md-2 control-label">订单号:</label>
                                <div class="col-md-6">
                                    <?=$model['sn']?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">创建时间</label>
                                <div class="col-md-6">
                                    <?=$model['addtime']?date('Y-m-d H:i:s',$model['addtime']):''?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">当前状态:</label>
                                <div class="col-md-6" style="color: red">
                                    <?=\common\models\OrderList::getState($model['state'])?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">商品信息:</label>
                                <div class="col-md-8" style="">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>名称</th>
                                            <th>缩略图</th>
                                            <th>价格</th>
                                            <th>数量</th>
                                            <th>备注</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($cart_info as $vo){?>
                                            <tr>
                                                <td><?=$vo['linkProduct']['name']?></td>
                                                <td><img src="<?=$vo['linkProduct']['image']?>" width="50px" height="25px" alt=""></td>
                                                <td><?=$vo['price']?></td>
                                                <td><?=$vo['guige']?>(规格) * <?=$vo['num']?>(份)</td>
                                                <td>{$val.cart_name}</td>
                                            </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">产品金额:</label>
                                <div class="col-md-6" style="color: red">
                                    $<?=$model['prices']?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">快递金额:</label>
                                <div class="col-md-6" style="color: red">
                                    $<?=$model['prices_kuaidi']?$model['prices_kuaidi']:0?>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label">订单总金额:</label>
                                <div class="col-md-6" style="color: red">
                                    $<?=$model['prices']+$model['prices_kuaidi']?>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-md-2 control-label">需求留言</label>
                                <div class="col-md-6">
                                    <?=$model['message']?>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label">支付情况:</label>
                                <div class="col-md-6" style="color: blue">
                                    <?php if($model['pay']==1){ ?>
                                        <p>已支付 支付时间：<?=$model['pay_addtime']?date('Y-m-d H:i:s',$model['pay_addtime']):''?></p>
                                    <?php }else{ ?>
                                        <p>未支付</p>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">支付类型:</label>
                                <div class="col-md-6" style="color: blue">
                                    <?=$model['payment']?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">发票类型</label>
                                <div class="col-md-6">
                                    <?=\common\models\OrderList::getFapiao($model['fapiao'])?>
                                </div>
                            </div>

                            <?php if($model['fapiao']==2){ ?>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">发票类型</label>
                                    <div class="col-md-6">
                                        <p>单位名称:<?=$model['companyname']?></p>
                                        <p>纳税人识别号:<?=$model['taxpayernumber']?></p>
                                        <p>注册地址:<?=$model['registeaddress']?></p>
                                        <p>注册电话:<?=$model['registetelephone']?></p>
                                        <p>开户银行:<?=$model['bank']?></p>
                                        <p>银行帐号:<?=$model['bankaccount']?></p>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-md-2 control-label">下单用户</label>
                                <div class="col-md-6">
                                    <?=$model['linkMember']['username']?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">下单用户会员号</label>
                                <div class="col-md-6">
                                    <?=$model['linkMember']['number']?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">配送方式:</label>
                                <div class="col-md-6">
                                    <p><?=\common\models\OrderList::getFapiao($model['tihuo_type'])?></p>
                                </div>
                            </div>
                            <?php if($model['tihuo_type']!=1){ ?>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">收货人</label>
                                    <div class="col-md-6">
                                        <?=$model['linkRecAddr']['name']?>
                                        {$data.addr.name}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">联系电话</label>
                                    <div class="col-md-6">
                                        <?=$model['linkRecAddr']['phone']?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">收货地址</label>
                                    <div class="col-md-6">
                                        <?=$model['linkRecAddr']['province']?><?=$model['linkRecAddr']['district']?><?=$model['linkRecAddr']['detail']?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">邮政编码</label>
                                    <div class="col-md-6">
                                        <?=$model['linkRecAddr']['youzheng']?>
                                    </div>
                                </div>

                            <?php } ?>

                            <div class="form-group">
                                <label class="col-md-2 control-label">快递名称</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="<?=$model['wuliuname']?>" name="wuliuname">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">快递号码：</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="<?=$model['wuliu']?>" name="wuliu">
                                </div>
                                <!--修改 20171214-->
                                <span><a href="http://www.kuaidi100.com/" target="_blank">查找快递信息</a></span>
                                <!--修改结束 20171214-->
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-6">
                                    <input type="hidden" name="iid" value="<?=$model['id']?>">
                                    <input type="submit" class="btn btn-block btn-primary btn-flat" value=" 修改 "/>
                                    <input type="button" onclick="history.back()" class="btn btn-block btn-primary btn-flat" value=" 返回 " style="background: #616263" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>





<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>

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
