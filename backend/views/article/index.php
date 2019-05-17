<?php

    $this->params = [
            'crumb'          => ['系统设置','文章管理','文章列表'],
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
                    <th width="250">文章标题</th>
                    <th width="100">栏目</th>
                    <th width="80">推荐</th>
                    <th width="150">时间</th>
                    <th width="100">状态</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$vo['id']?></td>
                        <td title="<?=$vo['title']?>"><?=mb_strlen($vo['title'],'utf8')>20?mb_substr($vo['title'],0,20,'utf8').'.....':$vo['title']?></td>
                        <td><?=$vo['linkNavPage']['name']?></td>
                        <td><?=\common\models\Article::getPropInfo('fields_is_up',$vo['is_up'])?></td>
                        <td><?=$vo['addtime']?date('Y-m-d H:i:s',$vo['addtime']):''?> </td>
                        <td><?=\common\models\Article::getPropInfo('fields_status',$vo['status'])?></td>
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
