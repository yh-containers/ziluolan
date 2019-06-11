<?php
$this->title = '我的订单';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<?php $this->endBlock()?>

<?php $this->beginBlock('content')?>

<div class="header"> <a href="<?=\yii\helpers\Url::to(['mine/index'])?>" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="content order">

    <!-- 业务范围-->
    <div class="class-nav clearfix">
        <ul class="clearfix wrap">
            <li style="width: 25%;" class="left fl <?=empty($state)?'cur':''?> "><a href="<?=\yii\helpers\Url::to([''])?>">全部</a></li>
            <li style="width: 25%;" class="left fl <?=$state==1?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['','state'=>1])?>">待付款</a></li>
            <li style="width: 25%;" class="left fr <?=$state==3?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['','state'=>3])?>">待发货</a></li>
            <li style="width: 25%;" class="left fr <?=$state==4?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['','state'=>4])?>">待收货</a></li>
            <li style="width: 25%;" class="left fr <?=$state==2?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['','state'=>2])?>">已完成</a></li>
        </ul>
    </div>
    <div class="pick">
        <ul class="orderlist" id="demo">

        </ul>
    </div>
</div>
<?=\frontend\widgets\Footer::widget(['current_action'=>'mine'])?>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    var url = "<?=\yii\helpers\Url::to(['show-list','state'=>$state])?>"
    var detail = "<?=\yii\helpers\Url::to(['detail'])?>";
    var pay_url = '<?=\yii\helpers\Url::to(['pay'])?>';
    var receive_url = '<?=\yii\helpers\Url::to(['receive'])?>';
    var cancel_order_url = '<?=\yii\helpers\Url::to(['cancel'])?>';
    var del_order_url = '<?=\yii\helpers\Url::to(['del'])?>';


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
                        var html ='';
                        var handle=[];
                        var goods = item.hasOwnProperty('goods_data')?(Array.isArray(item.goods_data)?item.goods_data:[]):[];
                        if(item.hasOwnProperty('handle'))  handle=item.handle?item.handle:[];
                        html +='<li class="clearfix">\n' +
                            '                        <div class="head clearfix">\n' +
                            '                            <span class="fl">订单编号：'+item.no+'</span>\n' +
                            '                            <div class="quantity fr">当前状态：\n' +
                            '                                <span style="color: red"> '+item.status_name+'</span>\n' +
                            '                            </div>\n' +
                            '                        </div>\n' ;
                        goods.map(function (goods_item,goods_index) {
                            html+='                        <a href="'+detail+(detail.indexOf('?')===-1?'?':'&')+'id='+item.id+'">\n' +
                                '                            <div class="img clearfix">\n' +
                                '                                <div class="pic fl">\n' +
                                '                                    <img class="tran" src="'+goods_item.img+'">\n' +
                                '                                </div>\n' +
                                '                                <div class="content fl">\n' +
                                '                                    <p class="title">'+goods_item.name+'('+goods_item.sku_name+')'+'</p>\n' +
                                '                                    <p class="price">价格：￥'+goods_item.price+' </p>\n' +
                                '                                    <p class="date">数量：'+goods_item.num+' </p>\n' +
                                '                                </div>\n' +
                                '                            </div>\n' +
                                '                        </a>\n' ;
                        })
                       html+='<div class="total-pay">' +
                           '<div class="clearfix"><span class="date">下单时间：'+item.create_time+'</span>'+
                           '<span class="price">总金额：<font>'+item.pay_money+'</font></span></div>'+
                           '<p class="message">备注：'+item.remark+'</p>' +
                           '</div>';

                        html+='<div class="status">\n' +
                              '<a href="'+detail+(detail.indexOf('?')===-1?'?':'&')+'id='+item.id+'" class="btn mod_btn">查看详情</a>\n';
                        //取消订单
                       if(handle.indexOf('cancel')!==-1){
                          html+='<a href="javascript:;"' +
                                'onclick="$.common.reqInfo(this,{confirm_title:\'是否取消订单\'})"'  +
                                'data-conf="{url:'+"'"+cancel_order_url+"'"+',data:{id:'+"'"+item.id+"'"+'},success:refresh_page}"' +
                                ' class="btn">取消订单</a>\n';
                       }
                        //删除订单
                       if(handle.indexOf('del')!==-1){
                          html+='<a href="javascript:;" ' +
                                'onclick="$.common.reqInfo(this,{confirm_title:\'是否删除订单\'})"'  +
                                'data-conf="{url:'+"'"+del_order_url+"'"+',data:{id:'+"'"+item.id+"'"+'},success:refresh_page}"' +
                                ' class="btn">删除订单</a>\n';
                       }
                        //支付订单
                       // if(handle.indexOf('pay')!==-1){
                       //    html+='<a href=" '+detail+(detail.indexOf('?')===-1?'?':'&')+'id='+item.id+'"  class="btn">支付</a>\n';
                       // }
                        //确认收货
                       if(handle.indexOf('receive')!==-1){
                         html+='<a href="javascript:;" ' +
                                'class="btn btn_confirm"' +
                                'onclick="$.common.reqInfo(this,{confirm_title:\'确定收货?\'})"'+
                                'data-conf="{url:'+"'"+receive_url+"'"+',data:{id:'+"'"+item.id+"'"+'},success:refresh_page}" '+
                                '>确认收货</a>\n';
                       }

                        html+='                        </div>\n' +
                            '                    </li>';
                        lis.push(html);
                    });

                    //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                    //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                    next(lis.join(''), page < res.pages);
                });
            }
        });
    });
    //刷新页面
    function refresh_page(res){
        layui.layer.msg(res.msg)
        if(res.code===1){
            setTimeout(function(){location.reload()},1000)
        }
    }
</script>
<?php $this->endBlock()?>


