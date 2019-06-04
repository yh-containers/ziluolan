<?php
$this->title = '银行卡管理';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>
    .list ul li {position: relative;}
    .piaochecked {width: 25px; height: 25px; display: inline-block;vertical-align: middle;cursor: pointer; text-align: center;background:url(<?=\Yii::getAlias('@assets')?>/images/icon_radio3.png) no-repeat center / 100%;}
    .list ul li.on_checkbox .piaochecked {background: url(<?=\Yii::getAlias('@assets')?>/images/icon_radio4.png) no-repeat center / 100% !important;}
    .piaochecked input {width: 100%;height: 100%;background: none;border:none;opacity: 0;position: absolute;left: 0px;top: 0px;}
    .bank {display: inline-block;vertical-align: middle;width: calc(100% - 40px);margin-left: 10px;}
    .address-footer {padding: 0px;}
    .bank_btn {display: block;width: 100%;height: 100%;color: #fff;font-size: 1rem;background: #7d1f88;}
    .bank-add {width: 92%; height: 45px;background: #fff;margin: 30px auto; box-sizing: border-box; border: 1px solid #7d1f88; border-radius: 4px; }
    .bank-add>a {display: block; font-size: 1rem; text-align: center; padding:0px 18px; line-height: 45px; color: #7d1f88; }
    #addr_skid {}
    #addr_skid li{}
    #addr_skid li .right{background: url(<?=\Yii::getAlias('@assets')?>/images/icon10.png) no-repeat right center / 30px 30px;;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header">
    <a href="javascript:window.history.go(<?=$channel?-3:-1 ?>)" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
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
    <a href="<?=\yii\helpers\Url::to(['bank-card-add'])?>"><div class="address-footer clearfix"><i></i>新增银行卡</div></a>
</div>



<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    var channel = "<?=$channel?>";

    var url = "<?=\yii\helpers\Url::to(['mine/bank-card-list'])?>"
    var detail = "<?=\yii\helpers\Url::to(['mine/bank-card-add'])?>";
    var opt_url = '<?=\yii\helpers\Url::to(['mine/bank-card-del'])?>';
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
                            '            <div class="left fl" data-id="'+item.id+'">\n'+
                            '              <p style="color:#333;font-size:1rem;">'+item.name+'</p>\n'+
                            '              <p><span class="span" style="color:#666;font-size:0.75rem;">尾号'+item.number+'</p>\n'+
                            '            </div>            \n'+
                            '          <a href="'+detail+(url.indexOf('?')===-1?'?':'&')+'id='+item.id+'" class="bank">\n'+
                            '            <div class="right fr" style=""></div>\n'+
                            '          </a>\n'+
                            '        </li>');
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
            if(channel.length>0){
                var id = $(this).data('id');
                var up_href = document.referrer
                window.location.href= up_href+(up_href.indexOf('?')===-1?'?':'&')+'bank_id='+id+(up_href.indexOf('channel')===-1?('&channel='+channel):'')
            }
        })


    </script>
    <?php $this->endBlock()?>

