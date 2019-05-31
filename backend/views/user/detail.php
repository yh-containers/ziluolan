<?php
$this->params=[
        'current_active'=>['user','user/index']
];
?>
<?php $this->beginBlock('style')?>

<style type="text/css">
.layui-table[lay-size=lg] th{ text-align: center;}
.layui-table[lay-size=lg] td,.layui-table[lay-size=lg] th{padding: 12px 10px; }
.box-body>a{margin: 5px 2px;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="row">
    <div class="col-sm-9">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">用户基本资料</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="layui-table" lay-size="lg">
                    <colgroup>
                        <col width="150">
                        <col width="190">
                        <col width="150">
                        <col width="190">
                        <col width="150">
                        <col width="190">
                        <col width="150">
                        <col width="190">
                    </colgroup>
                    <tbody>
                    <tr>
                        <td>会员号</td>
                        <td><?=$model['number']?></td>
                        <td>真实姓名</td>
                        <td><?=$model['usersname']?></td>
                        <td>性别</td>
                        <td><?=\common\models\User::getPropInfo('fields_sex',$model['sex'])?></td>
                        <td>所属门店</td>
                        <td><?=$model['linkAdmin']['name']?></td>
                    </tr>
                    <tr>
                        <td>微信名称</td>
                        <td><?=$model['username']?></td>
                        <td>等级</td>
                        <td><?=\common\models\User::getPropInfo('fields_consume_type',$model['consume_type'],'name')?></td>
                        <td>生日</td>
                        <td><?=$model['birthday']?></td>
                        <td>手机号</td>
                        <td><?=$model['phone']?></td>
                    </tr>
                    <tr>
                        <td>城市</td>
                        <td><?=$model['address']?></td>
                        <td>微信号</td>
                        <td><?=$model['weixin']?></td>
                        <td></td>
                        <td></td>
                        <td>推荐人</td>
                        <td><?=$model['linkUserUp']['number']?></td>
                    </tr>
                    <tr>
                        <td>健康豆</td>
                        <td><?=$model['deposit_money']?></td>
                        <td>金豆</td>
                        <td></td>
                        <td>消费金豆</td>
                        <td><?=$model['consum_wallet']?></td>
                        <td>钱包金额</td>
                        <td><?=$model['wallet']?></td>
                    </tr>
                    <tr>
                        <td>团队业绩</td>
                        <td><?=$model['team_wallet_full']?></td>
                        <td>团队提成</td>
                        <td><?=$model['team_wallet']?></td>
                        <td>总消费额度</td>
                        <td><?=$model['consum_money']?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>购买次数</td>
                        <td><?=empty($model['linkOrderCount']['order_num'])?0:$model['linkOrderCount']['order_num']?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>注册时间</td>
                        <td><?=$model['create_time']?date('Y-m-d H:i:s',$model['create_time']):''?></td>
                        <td>最近登录</td>
                        <td><?=$model['dl_addtime']?date('Y-m-d H:i:s',$model['dl_addtime']):''?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>


            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">收货地址</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body text-center">
                    <table class="layui-table" lay-size="lg">
                        <thead>
                        <tr>
                            <th>收货人名</th>
                            <th>电话</th>
                            <th>地址</th>
                            <th>邮编</th>
                            <th>更新时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($model['linkRecAddr'])) foreach ($model['linkRecAddr'] as $vo){ ?>
                        <tr>
                            <td><?=$vo['username']?></td>
                            <td><?=$vo['phone']?></td>
                            <td><?=$vo['addr'].' '.$vo['addr_extra']?></td>
                            <td><?=$vo['zip_code']?></td>
                            <td><?=empty($vo['update_time'])?'--':date('Y-m-d H:i:s',$vo['update_time'])?></td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">银行卡</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">
                <table class="layui-table" lay-size="lg" id="layer-photos-demo">
                    <thead>
                    <tr>
                        <th width="200">银行</th>
                        <th width="200">卡号</th>
                        <th width="150">卡户主</th>
                        <th width="150">联系电话</th>
                        <th width="100">身份证</th>
                        <th width="100">更新日期</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($model['linkBankCard'])) foreach ($model['linkBankCard'] as $vo){ ?>
                        <tr>
                            <td><?=$vo['name']?></td>
                            <td><?=$vo['number']?></td>
                            <td><?=$vo['username']?></td>
                            <td><?=$vo['phone']?></td>
                            <td><img src="<?=$vo['up']?>" alt="身份证正面"><img src="<?=$vo['down']?>" alt="身份证反面"></td>
                            <td><?=empty($vo['update_time'])?'--':date('Y-m-d H:i:s',$vo['update_time'])?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">我推荐的用户</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">
                <table class="layui-table" lay-size="lg">
                    <thead>
                    <tr>
                        <th>会员号</th>
                        <th>用户名</th>
                        <th>手机号</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($model['linkChild'])) foreach ($model['linkChild'] as $vo){ ?>
                        <tr>
                            <td><?=$vo['number']?></td>
                            <td><?=$vo['username']?></td>
                            <td><?=$vo['phone']?></td>
                        </tr>
                    <?php }?>

                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>

    </div>


    <div class="col-sm-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">操作</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            </div>
            <!-- /.box-body -->
        </div>


        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">近期流水信息</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="layui-timeline">
                    <?php if(!empty($model['linkUserLog'])) foreach ($model['linkUserLog'] as $vo){ ?>
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title"><?=$vo['create_time']?></h3>
                            <p>
                                <?=$vo['intro']?>
                            </p>
                        </div>
                    </li>
                    <?php }?>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
</div>





<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>
    layui.use(['layer'], function(){
        var layer = layui.layer;

        layer.photos({
            photos:"#layer-photos-demo"
        })

    });


</script>
<?php $this->endBlock()?>
