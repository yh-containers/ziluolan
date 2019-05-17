<?php

$this->params = [
    'crumb'          => ['系统设置','管理员管理','角色管理'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['roles-add'])?>" class="btn bg-olive margin"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>新增</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <colgroup>
                    <col width="120">
                    <col width="120">
                    <col width="80">
                    <col width="80">
                    <col width="80">
                </colgroup>
                <thead>
                <tr>
                    <th>一级角色</th>
                    <th>二级角色</th>
                    <th>状态</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model as $vo){?>
                    <tr>
                        <td rowspan="<?=count($vo['linkRoles'])+1?>"><a href="<?=\yii\helpers\Url::to(['roles-add','id'=>$vo['id']])?>"><?=$vo['name']?></a></td>
                        <td>--</td>
                        <td><?=\common\models\SysRole::getStatusName($vo['status'])?></td>
                        <td><?=$vo->updateTime?></td>
                        <td>
                            <a  class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['roles-add','id'=>$vo['id']])?>">编辑</a>
                            <a  class="layui-btn layui-btn-danger layui-btn-sm"  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['roles-del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
                        </td>
                    </tr>
                    <?php foreach($vo['linkRoles'] as $item){?>
                        <tr>
                            <td><a href="<?=\yii\helpers\Url::to(['roles-add','id'=>$item['id']])?>"><?=$item['name']?></a></td>
                            <td><?=\common\models\SysRole::getStatusName($item['status'])?></td>
                            <td><?=$item->updateTime?></td>
                            <td>
                                <a  class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['roles-add','id'=>$item['id']])?>">编辑</a>
                                <a  class="layui-btn layui-btn-danger layui-btn-sm"  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['roles-del','id'=>$item['id']])?>','删除')" class="ml-5">  删除</a>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->

    </div>


<?php $this->endBlock()?>