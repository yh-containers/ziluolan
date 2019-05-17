<?php

    $this->params = [
            'crumb'          => ['系统设置','管理员列表'],
    ];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['manager-add'])?>" class="btn bg-olive margin">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                新增管理员
            </a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>用户名</th>
                    <th>手机号码</th>
                    <th>角色</th>
                    <th>帐号</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$vo['name']?></td>
                        <td><?=$vo['phone']?></td>
                        <td><?=$vo['userRoleName']?></td>
                        <td><?=$vo['account']?> </td>
                        <td><?=$vo['updateTime']?></td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['manager-add','id'=>$vo['id']])?>">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-sm" href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['manager-del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
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