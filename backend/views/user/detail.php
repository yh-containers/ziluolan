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

<div class="row">
    <div class="col-sm-8">
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
                        <td></td>
                        <td>真实姓名</td>
                        <td></td>
                        <td>性别</td>
                        <td></td>
                        <td>所属门店</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>微信名称</td>
                        <td></td>
                        <td>等级</td>
                        <td></td>
                        <td>生日</td>
                        <td></td>
                        <td>手机号</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>城市</td>
                        <td></td>
                        <td>微信号</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>推荐人</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>健康豆</td>
                        <td></td>
                        <td>金豆</td>
                        <td></td>
                        <td>消费金豆</td>
                        <td></td>
                        <td>钱包金额</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>团队业绩</td>
                        <td></td>
                        <td>团队提成</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>购买次数</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>注册时间</td>
                        <td></td>
                        <td>最近登录</td>
                        <td></td>
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
                        <tr>
                            <td></td>
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

        <div class="col-sm-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">银行卡</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body text-center">
                    <table class="layui-table" lay-size="lg">
                        <thead>
                        <tr>
                            <th>用户名</th>
                            <th>开户行</th>
                            <th>卡号</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">我推荐的用户</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body text-center">
                    <table class="layui-table" lay-size="lg">
                        <thead>
                        <tr>
                            <th>用户名</th>
                            <th>开户行</th>
                            <th>卡号</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

    </div>


    <div class="col-sm-4">
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

            </div>
            <!-- /.box-body -->
        </div>

    </div>
</div>





<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>



</script>
<?php $this->endBlock()?>
