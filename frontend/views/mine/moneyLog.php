<?php
$this->title = '消费明细';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header">
    <a href="<?=\yii\helpers\Url::to(['withdraw'])?>" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<div class="content order">

    <!-- 业务范围-->
    <div class="class-nav clearfix">
        <ul class="clearfix wrap">
            <li style="width: 25%;" class="left fl  <?=empty($state)?'cur':''?>"><a href="<?=\yii\helpers\Url::to([''])?>">全部</a></li>
            <li style="width: 25%;" class="left fl <?=$state==1?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['','state'=>1])?>">商品购买</a></li>
            <li style="width: 25%;" class="left fr <?=$state==2?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['','state'=>2])?>">提成收入</a></li>

        </ul>
    </div>
    <div class="pick">
        <div class="wrap clearfix">
            <ul class="clearfix" id="addr_skid">

            </ul>
        </div>
    </div>


</div>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

    var url = "<?=\yii\helpers\Url::to(['mine/money-log-list','state'=>$state])?>"
    layui.use(['flow','layer'], function(){
        var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
        var flow = layui.flow;
        flow.load({
            elem: '#addr_skid' //指定列表容器
            ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                var lis = [];
                //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                $.get(url+(url.indexOf('?')===-1?'?':'&')+'page='+page, function(res){
                    //假设你的列表返回在data集合中
                    layui.each(res.data, function(index, item){
                        lis.push('<li class="wow fadeInDown animate clearfix">\n' +
                            '                        <div class="img">\n' +
                            '                            <span>时间：</span><span>'+item.create_time+'</span>\n' +
                            '                            <div class="content fl">\n' +
                            '                                <p>'+item.intro+'</p>\n' +
                            '                                <p class="quantity">类别：\n' +
                            '                                    <span style="color: red">'+item.type+'</span>\n' +
                            '                                </p>\n' +
                            '                            </div>\n' +
                            '                        </div>\n' +
                            '                    </li>');
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

