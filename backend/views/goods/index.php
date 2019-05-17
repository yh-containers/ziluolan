<?php

    $this->params = [
            'crumb'          => ['系统设置','商品管理','商品列表'],
    ];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="layer-photos-demo">
                <thead>
                <tr>
                    <th width="80">ID</th>
                    <th width="250">商品名称</th>
                    <th width="100">商品价格</th>
                    <th width="80">分佣模式</th>
                    <th width="150">更新时间</th>
                    <th width="100">状态</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$vo['id']?></td>
                        <td title="<?=$vo['name']?>"><?=mb_strlen($vo['name'],'utf8')>20?mb_substr($vo['name'],0,20,'utf8').'.....':$vo['name']?></td>
                        <td><?=empty($vo['linkSku'])?0.00:$vo['linkSku'][0]['price']?></td>
                        <td><?=\common\models\Goods::getPropInfo('fields_mode',$vo['mode'],'name')?></td>
                        <td><?=$vo['update_time']?date('Y-m-d H:i:s',$vo['update_time']):''?> </td>
                        <td><?=\common\models\Goods::getPropInfo('fields_status',$vo['status'])?></td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-sm" href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
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
