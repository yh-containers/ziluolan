<?php

    $this->params = [
            'crumb'          => ['系统设置','导航栏管理','导航栏列表'],
    ];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="layer-photos-demo">
                <thead>
                <tr>
                    <th width="80">ID</th>
                    <th width="200">名称</th>
                    <th width="200">导航链接</th>
                    <th width="100">状态</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $vo) {?>
                    <tr>
                        <td><?=$vo['id']?></td>
                        <td><?=$vo['name']?></td>
                        <td><?=$vo['url']?></td>
                        <td><?=\common\models\SysNav::getPropInfo('fields_status',$vo['status'])?></td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['nav-add','id'=>$vo['id']])?>">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-sm" href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['nav-del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
                        </td>
                    </tr>
                    <?php foreach ($vo['linkNav'] as $item) {?>
                        <tr>
                            <td><?=$item['id']?></td>
                            <td>&nbsp;&nbsp;┡━<?=$item['name']?></td>
                            <td><?=$item['url']?></td>
                            <td><?=\common\models\SysNav::getPropInfo('fields_status',$item['status'])?></td>
                            <td>
                                <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['nav-add','id'=>$item['id']])?>">编辑</a>
                                <a class="layui-btn layui-btn-danger layui-btn-sm" href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['nav-del','id'=>$item['id']])?>','删除')" class="ml-5">  删除</a>
                            </td>
                        </tr>

                        <?php foreach ($item['linkNav'] as $ch_item) {?>
                            <tr>
                                <td><?=$ch_item['id']?></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;┡━<?=$ch_item['name']?></td>
                                <td><?=$ch_item['url']?></td>
                                <td><?=\common\models\SysNav::getPropInfo('fields_status',$ch_item['status'])?></td>
                                <td>
                                    <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['nav-add','id'=>$ch_item['id']])?>">编辑</a>
                                    <a class="layui-btn layui-btn-danger layui-btn-sm" href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['nav-del','id'=>$ch_item['id']])?>','删除')" class="ml-5">  删除</a>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
    </div>


<?php $this->endBlock()?>
<?php $this->beginBlock('script');?>
<script>
    layui.use(['layer'], function(){
        var layer = layui.layer;

        layer.photos({
            photos:"#layer-photos-demo"
        })

    });
    $(function(){

    })
</script>
<?php $this->endBlock();?>
