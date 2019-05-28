<?php
$this->title = '购物车';
$this->params = [
];
?>

<?php $this->beginBlock('content')?>
<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo">购物车</div>
</div>
<div class="clearfix" style="height:60px;"> </div>

<div class="content">
    <div class="shopping">
        <div class="shop-group-item">
            <div class="shop-name">
                <!--  <input type="checkbox" class="check goods-check shopCheck">
                 <span style="padding-left:30px;" onclick="onShopAll(this,'true')">全选</span> --> </div>
            <?php if($list){ ?>
                <ul>
                    <?php foreach($list as $vo) { ?>
                        <li>
                            <div class="shop-info">
                                <input type="checkbox" name="checkbox" data-id="<?=$vo['id']?>" <?=$vo['is_checked']?'checked':''?> />
                                <div class="shop-info-img">
                                    <a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$vo['gid'],'sku_id'=>$vo['sid']])?>"><img src="<?=\common\models\Goods::getCoverImg($vo['linkGoods']['image'])?>"></a></div>
                                <div class="shop-info-text">
                                    <h4><a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$vo['gid'],'sku_id'=>$vo['sid']])?>"><?=$vo['linkGoods']['name']?></a></h4>

                                    <div class="shop-price clearfix">
                                        <if condition="$val.shop_type NEQ '无'">
                                            <div class="">型号:<?=$vo['attr_name']?><br /></div>
                                        </if>
                                        <div class="fl"><div class="shop-pices">单价:￥<b class="goods-price" ><?=$vo['linkSkuAttrPrice']['price']?></b><br /></div>
                                            <input type="hidden" name="price[]" value="" />
                                            <div class="shop-arithmetic">
                                                <a href="javascript:;" class="opt-num" data-num_step="-1" data-sku_id="<?=$vo['sid']?>" data-gid="<?=$vo['gid']?>" >-</a>
                                                <input type="text" value="<?=$vo['num']?>" id="<?=$vo['id']?>" data-gid="<?=$vo['id']?>" class="num gods-num" readonly">
                                                <a href="javascript:;" class="opt-num" data-num_step="1" data-sku_id="<?=$vo['sid']?>" data-gid="<?=$vo['gid']?>">+</a>

                                            </div></div>
                                        <span class="fr"><a href="javascript:;" onclick="on_cart_del(<?=$vo['id']?>)">删除</a></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php }?>
                </ul>
            <?php }else{ ?>
                <div class="no-orders" style="text-align:center;">
                    <img style="width:120px;" src="<?=\Yii::getAlias('@assets')?>/images/nocart.png">
                    <span>您购物车是空的，赶快去下单吧</span>
                </div>
            <?php } ?>
        </div>
    </div>
        <div class="payment-bar">
            <div class="all-checkbox">
                <input  type="checkbox" class="check goods-check" id="AllCheck">&nbsp;
                <!--  全选
                 <input onclick="onShopAll(this,'false')" type="checkbox" class="check goods-check" id="AllCheck">&nbsp;
                 删除选中 -->
            </div>
            <input type="hidden" value="{$z_price}" class="z_price">
            <a href="javascript:;" class="settlement" id="submit">结算</a>
            <div class="shop-total">
                <strong>总价</strong>
                <span class="yellow" id="total-money">￥ 0.00</span>
            </div>
        </div>


</div>


<?=\frontend\widgets\Footer::widget(['current_action'=>'index'])?>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

    $(function(){
        //购物车增、减
        $(".opt-num").click(function(){
            var $this=$(this)
            var num = parseInt($(this).parent().find('input').val())
            var num_step = parseInt($(this).data('num_step'))
            var gid = $(this).data('gid')
            var sku_id = $(this).data('sku_id')
            if(num+num_step<=0){
                return false;
            }
            $.common.reqInfo({url:'<?=\yii\helpers\Url::to(['mine/add-cart'])?>',data:{gid:gid,sku_id:sku_id,num_step:num_step},success:function(){
                $this.parent().find('input').val(num+num_step)
                cal_page()
            }})
        })
        //商品选中效果
        $("input[type='checkbox']").change(function(){
            var is_checked = $(this).prop('checked')
            var id = $(this).data('id')
            console.log(is_checked)
            var obj = {}
            if(id){
                obj.cart_id=id
            }else{
                //全选
                obj.is_checked = is_checked?1:0;
                $("input[type='checkbox']").prop('checked',is_checked)
            }
            $.common.reqInfo({url:'<?=\yii\helpers\Url::to(['mine/cart-choose'])?>',data:obj,success:function(){
                    cal_page()
            }})
        })

        $("#submit").click(function(){
            var is_allow_redirect = false;
            $(".shop-group-item input[type='checkbox']").each(function(){
                if($(this).prop('checked')){
                    is_allow_redirect = true;
                }
            })
            if(!is_allow_redirect){
                alert('请选择购买的商品');
                return false;
            }
            window.location.href="<?=\yii\helpers\Url::to(['order/info','channel'=>'cart'])?>"
        })


    })

    //删除购物车
    function on_cart_del(c_ids){
        $.common.reqInfo({url:'<?=\yii\helpers\Url::to(['mine/cart-del'])?>',data:{c_ids:c_ids},success:function(res){
                layui.layer.msg(res.msg)
                if(res.code ==1){
                    setTimeout(function(){location.reload()},1000)
                }
        }},{confirm_title:"是否删除该商品"})
    }

    //计算价格
    cal_page()
    function cal_page() {
        var total_money = 0.00;
        var is_checked_len = 0;
        $(".shop-group-item li").each(function(){
            var is_checked = $(this).find("input[name='checkbox']").prop('checked')
            var num = $(this).find(".gods-num").val()
            num=num?parseInt(num):1
            var price = $(this).find('.goods-price').text()
            price=price?parseFloat(price):0.00
            console.log(num)
            if(is_checked){
                is_checked_len++;
                total_money=total_money+price*num;
            }
            $("#total-money").text(total_money.toFixed(2))
        })

        full_checked()

    }

    //全选按钮问题
    function full_checked(){
        $("#AllCheck").prop('checked',$(".shop-group-item li input[name='checkbox']:checked").length===$(".shop-group-item li").length)

    }
</script>
<?php $this->endBlock()?>

