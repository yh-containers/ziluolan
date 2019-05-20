<?php
$this->title = '紫罗兰花青素';
?>

<?php $this->beginBlock('content')?>
<div class="header">
    <div class="logo">产品分类</div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="bk_gray"></div>
<div class="clearfix" style="height:60px;"> </div>
<div class="content">

    <div class="main clearfix">
        <ul class="inside_nav">
            <?php foreach ($model_cate as $vo){?>
                <li <?=$vo['id']==$n_id?'class="cur"':''?>><a href="<?=\yii\helpers\Url::to(['','n_id'=>$vo['id']])?>" ><?=$vo['name']?></a></li>
            <?php }?>
        </ul>
        <div class="product_content">
            <ul id="demo">

            </ul>
        </div>
    </div>

    <?=\frontend\widgets\Footer::widget(['current_action'=>'cate'])?>

<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>

<script>
    var url = "<?=\yii\helpers\Url::to(['goods/show-list','n_id'=>$n_id])?>"
    var detail = "<?=\yii\helpers\Url::to(['goods/detail'])?>";
    var cart_img = "<?=\Yii::getAlias('@assets')?>/images/car01.png";
    var opt_url = '<?=\yii\helpers\Url::to(['mine/add-cart'])?>';
    layui.use('flow', function(){
        var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
        var flow = layui.flow;
        flow.load({
            elem: '#demo' //指定列表容器
            ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                var lis = [];
                //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                $.get(url+(url.indexOf('?')===-1?'?':'&')+'page='+page, function(res){
                    //假设你的列表返回在data集合中
                    layui.each(res.data, function(index, item){
                        lis.push('<li>\n' +
                            '<div class="product_img"><a href="'+detail+(detail.indexOf('?')===-1?'?':'&')+'sku_id='+item.sku_id+'&id='+item.id+'"><img src="'+item.cover_img+'"></a></div>\n' +
                            '<div class="product_text">\n' +
                            '<p class="product_name"><a href="'+detail+(detail.indexOf('?')===-1?'?':'&')+'sku_id='+item.sku_id+'&id='+item.id+'">'+item.name+'</a></p>\n' +
                            '<p class="product_price">￥'+item.sku_price+'<a ' +
                            'href="javascript:;"' +
                            'onclick="$.common.reqInfo(this)" ' +
                            'data-conf="{url:'+"'"+opt_url+"'"+',data:{gid:'+"'"+item.id+"'"+',sku_id:'+"'"+item.sku_id+"'"+'}}"' +
                            '><img src="'+cart_img+'"></a></p>\n' +
                            '</div>\n' +
                            '</li>');
                            });

                            //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                            //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                            next(lis.join(''), page < res.pages);
                        });
                    }
                });
            });


        </script>


        <?php $this->endBlock()?>
