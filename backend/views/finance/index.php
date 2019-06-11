<?php

$this->params = [
    'crumb'          => ['系统设置','财务管理','财务列表'],
];
?>
<?php $this->beginBlock('content')?>



<div class="box">
    <div class="box-header with-border">
        <h3>财务统计</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="col-sm-6">
            <table class="layui-table" lay-size="lg">
                <colgroup>
                    <col width="150">
                    <col width="190">
                </colgroup>
                <tbody>
                <tr class="bg-gray">
                    <td colspan="2">模式产品</td>
                </tr>
                <tr>
                    <td>营业额(不含运费)</td>
                    <td></td>
                </tr>
                <tr>
                    <td>提成支出</td>
                    <td></td>
                </tr>
                <tr>
                    <td>固定奖支出</td>
                    <td></td>
                </tr>
                <tr>
                    <td>运费收入</td>
                    <td></td>
                </tr>
                <tr>
                    <td>平台利润</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <table class="layui-table" lay-size="lg">
                <colgroup>
                    <col width="50">
                    <col width="250">
                </colgroup>
                <tbody>
                <tr class="bg-gray">
                    <td colspan="2">普通产品</td>
                </tr>
                <tr>
                    <td>营业额(不含运费)</td>
                    <td></td>
                </tr>
                <tr>
                    <td>提成支出</td>
                    <td></td>
                </tr>
                <tr>
                    <td>固定奖支出</td>
                    <td></td>
                </tr>
                <tr>
                    <td>运费收入</td>
                    <td></td>
                </tr>
                <tr>
                    <td>平台利润</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>


<?php $this->endBlock()?>
<?php $this->beginBlock('script');?>
<script>

</script>
<?php $this->endBlock();?>
