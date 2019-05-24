<?php
$this->title = $title.($ch_title?'-'.$ch_title:'');
$this->params = [
        'meta_key' => $meta_key,
        'meta_desc' => $meta_desc,
];
?>

<?php $this->beginBlock('content')?>

<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>
<div class="clearfix" style="height:60px;"> </div>
<div class="main clearfix">
    <ul class="inside_nav">
        <?php
            foreach ($menu['linkNavPage'] as $vo){
                $route = \frontend\widgets\Nav::defineRoute($vo['route']);
                $url = !is_array($route)?$route:\yii\helpers\Url::to(array_merge($route,['id'=>$vo['id']]));
        ?>
            <li <?=$vo['id']==$id?'class="cur"':''?> ><a href=" <?=$url?>"><?=$vo['name']?></a></li>
        <?php }?>
    </ul>
    <div class="news_content">
        <ul id="demo">

        </ul>
    </div>
</div>


<?=\frontend\widgets\Footer::widget(['current_action'=>'index'])?>
<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>
    var url = "<?=\yii\helpers\Url::to(['show-list','id'=>$id,'con_type'=>$con_type])?>"
    var detail = "<?=\yii\helpers\Url::to(['detail','con_type'=>$con_type])?>"
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
                            '            <div class="news_left"><a href="'+detail+(detail.indexOf('?')===-1?'?':'&')+'menu_id='+item.cid+'&id='+item.id+'"><img src="'+item.image+'"></a></div>\n' +
                            '            <div class="news_right">\n' +
                            '              <a href="'+detail+(detail.indexOf('?')===-1?'?':'&')+'menu_id='+item.cid+'&id='+item.id+'">\n' +
                            '                <h2>'+item.title+'</h2>\n' +
                            '                <p class="news_time">'+item.addtime+'</p>\n' +
                            '                <p class="news_text">'+item.desc+'</p>\n' +
                            '              </a>\n' +
                            '            </div>\n' +
                            '          </li>');
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
