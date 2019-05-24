<?php
$this->title = '地址管理';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header">
    <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
        <div class="content">
    <div class="Personal address clearfix">
        <div class="list clearfix">
            <ul id="addr_skid">

            </ul>
        </div>
    </div>

    <!-- 底部-->
    <!-- 底部-->
    <div class="clearfix" style="height:100px;"> </div>
    <a href="<?=\yii\helpers\Url::to(['address-add'])?>"><div class="address-footer clearfix"><i></i>新增地址</div></a>
</div>



<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    var channel = "<?=$channel?>";

    var url = "<?=\yii\helpers\Url::to(['mine/address-list'])?>"
    var detail = "<?=\yii\helpers\Url::to(['mine/address-add'])?>";
    var opt_url = '<?=\yii\helpers\Url::to(['mine/address-del'])?>';
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
                        lis.push('<li class="clearfix">\n' +
                            '    <div class="left fl" data-id='+item.id+'>\n' +
                            '        <p><span>'+item.username+'</span><span>'+item.phone+'</span></p>\n' +
                            '        <p><span class="span1">'+(item.is_default?'[默认地址]':'')+'</span>'+item.addr+item.addr_extra+'</p>\n' +
                            '    </div>\n' +
                            '    <div class="right delete fr">\n' +
                            '       <a href="javascript:;"' +
                            'onclick="$.common.reqInfo(this,{confirm_title:'+"'是否删除该数据'"+'})" ' +
                            'data-conf="{url:'+"'"+opt_url+"'"+',data:{id:'+"'"+item.id+"'"+'},success:'+handleDel+'}"' +
                            '></a>\n' +
                            '    </div>\n' +
                            ' </li>');
                    });

                    //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                    //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                    next(lis.join(''), page < res.pages);
                });
            }
        });

        function handleDel(res){
            layer.msg(res.msg)
            if(res.code==1){
                setTimeout(function(){location.reload()},1000)
            }
        }
    });


    $("#addr_skid").on('click','li .left',function(){
        if(channel){
            var addr_id = $(this).data('id');
            var up_href = document.referrer
            // location.replace(up_href+(up_href.indexOf('?')===-1?'?':'&')+'addr_id='+addr_id+(up_href.indexOf('channel')===-1?('&channel='+channel):''))
            window.location.href= up_href+(up_href.indexOf('?')===-1?'?':'&')+'addr_id='+addr_id+(up_href.indexOf('channel')===-1?('&channel='+channel):'')
        }
    })


</script>
<?php $this->endBlock()?>

