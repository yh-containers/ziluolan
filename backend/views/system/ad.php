<?php

    $this->params = [
            'crumb'          => ['系统设置','广告列表'],
    ];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['ad-add'])?>" class="btn bg-olive margin">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                新增广告
            </a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="layer-photos-demo">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>栏目类型</th>
                    <th>图片</th>
                    <th>图片链接</th>
                    <th>设备</th>
                    <th>更新日期</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$vo['name']?></td>
                        <td><?=$vo['type']?></td>
                        <td><img src="<?=$vo['image']?>" alt="<?=$vo['title']?>" width="40px" height="40px"/></td>
                        <td><?=$vo['url']?></td>
                        <td><?=$vo['device']?> </td>
                        <td><?=$vo['updateTime']?></td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['ad-add','id'=>$vo['id']])?>">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-sm" href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['ad-del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
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
