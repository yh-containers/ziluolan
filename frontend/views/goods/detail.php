<?php
$this->title = $model['name'];

?>

<?php $this->beginBlock('style')?>
<style>
    #edit-class{}
    #edit-class .choose-sku-item.disable{background: #a8a8a8;color:#fff}
</style>
<?php $this->endBLock()?>

<?php $this->beginBlock('content')?>

<body style="background:#eee;">
<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo">商品详情</div>
</div>
<div class="content pro">
    <div class="flexslider">
        <ul class="slides">

            <?php
                $image = empty($model['image'])?[]:explode(',',$model['image']);
                foreach ($image as $vo){
            ?>
                <li><img src="<?=$vo?>" /></li>
            <?php }?>
        </ul>
    </div>


    <!-- 业务范围-->
    <div class="title bj1">
        <div class=" wrap">
            <div class="title1"><?=$model['name']?></div>


            <div class="title2">单价:<span class="span1" id="price">0.00</span></div>
            <div class="title3 clearfix"></div>
        </div>
    </div>

    <div class="title bj1" id="choose-sku">
        <div class=" wrap">
            <div class="title2">已选规格:<em></em></div>
            <div class="title3 clearfix"></div>
        </div>
    </div>
    
    
    <div class="slideTxtBox clearfix">
        <div class="hd bj1 clearfix">
            <ul>
                <li><i class="icon iconfont icon-gantanhao"></i>商品详情</li>
                <li><i class="icon iconfont icon-chengjiao" style="font-size:22px;"></i>商品规格</li>
            </ul>
        </div>
        <div class="bd clearfix" style="padding-top:10px;">
            <ul class="clearfix ul1 about wrap bj1" style="margin-top:-2px;">
                <?=$model['content']?>
            </ul>
            <ul class="clearfix wrap ul2 bj1">
                <?=$model['attr']?>
            </ul>

        </div>
    </div>

    <div class="clearfix" style="height:70px;"> </div>
    <!-- 底部-->
    <div class="payment-bar pro-footer clearfix">
        <a href="<?=\yii\helpers\Url::to(['mine/cart'])?>">
            <div class="icon fl"></div>
        </a>
        <a  href="javascript:void(0)" onclick="joinCart()">
            <div class="gouwuche fl choose-sku" id="gouwu">加入购物车</div>
        </a>
        <a href="javascript:;" onclick="buy()" ><div class="goumai fr">立即购买</div></a>

    </div>
</div>

<!-- 产品选择 -->
<div class="edit-class" id="edit-class">
    <div class="xiala clearfix" style="display: block;"><strong><a href="javascript:void(0)" id="choose-sku-hide"><i class="fr guanbi"></i></a></strong>
        <div class="wrap">
            <div class="title clearfix">选择商品规格</div>
            <?php foreach($model['linkSku'] as $vo){?>
            <div class="color-class clearfix">
                <p><?=$vo['name']?></p>
                <div class="size" style="border-bottom:none;">
                    <?php foreach($vo['linkSkuAttr'] as $sku){?>
                        <span class="freight-info choose-sku-item" data-id="<?=$sku['id']?>"><?=$sku['name']?></a></span>
                    <?php }?>

                </div>
            </div>
            <?php }?>
        </div>
    </div>
</div>
<div id="fade" class="black_overlay" style="display: none;"></div>
<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script src="<?=\Yii::getAlias('@assets')?>/js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script>
    //商品规格数据
    var sku_choose_info = <?=json_encode($sku_choose_info,JSON_UNESCAPED_UNICODE)?>;
    //商品规格数据-选择项为主键
    var sku_attr_info ={}
    for (var item in sku_choose_info){
        var key = '|'+sku_choose_info[item].sku_group+'|'
        sku_choose_info[item].sku_id = item
        sku_attr_info[key]=sku_choose_info[item]
    }

    console.log(sku_attr_info)
    //选择商品的sku
    var sku_id = "<?=$sku_id?>";
    if(sku_choose_info.hasOwnProperty(sku_id)){
        handle_sku_switch(sku_choose_info[sku_id].sku_group);
    }

    $(function(){
        jQuery(".slideTxtBox").slide({easing:"easeOutCirc"});
        $('.flexslider').flexslider({
            directionNav: true,
            pauseOnAction: false
        });
        
        //选择商品属性
        $("#choose-sku").click(function () {
            $("#edit-class").show();
            $("#fade").show();
        })
        $("#choose-sku-hide").click(function () {
            $("#edit-class").hide();
            $("#fade").hide();
        })

        //选择商品sku-attr
        $(".choose-sku-item").click(function(){
            if(!$(this).hasClass('cur') && !$(this).hasClass('disable')){
                //移除其它选项的选中效果
                $(this).parent().find('.cur').removeClass('cur')
                $(this).addClass('cur')
                var sku_id_group_arr = []
                //当前点击的所有上级
                $(this).parent().parent().prevAll().find('.cur').each(function () {
                    sku_id_group_arr.push($(this).data('id')+'')
                })
                //包含自己
                sku_id_group_arr.push($(this).data('id')+'')
                //当前点击的下级
                //全部禁用
                $(this).parent().parent().nextAll().find('.choose-sku-item').addClass('disable')
                $(this).parent().parent().next().find('.choose-sku-item').each(function(index){

                    let sku_id_group_arr_temp  = sku_id_group_arr;
                    let item_sku_id =$(this).data('id')+'';
                    if(index){
                        sku_id_group_arr_temp[sku_id_group_arr_temp.length-1]=item_sku_id
                    }else{
                        sku_id_group_arr_temp.push(item_sku_id)
                    }
                    let sku_id_group_arr_temp_str = sku_id_group_arr_temp.sort(sortNumber).join('|')
                    sku_id_group_arr_temp_str = '|'+sku_id_group_arr_temp_str+'|';
                    for (let item in sku_attr_info){
                        if($(this).hasClass('disable') && item.indexOf(sku_id_group_arr_temp_str)>-1){
                            $(this).removeClass('disable')
                        }
                    }
                })
                //移除未选择的所有项
                $(this).parent().parent().nextAll().find('.cur').removeClass('cur')
                handle_sku_switch()
            }
        })
    });
    //加入购物车
    function joinCart(){
        $.common.reqInfo({url:'<?=\yii\helpers\Url::to(['mine/add-cart'])?>',data:{gid:'<?=$model['id']?>',sku_id:sku_id}})
    }

    //立即购买
    function buy(){
        var url = '<?=\yii\helpers\Url::to(['order/info','gid'=>$model['id']])?>';
        window.location.href=url+(url.indexOf('?')===-1?'?':'&')+'sku_id='+sku_id
    }



    //切换商品展--选中效果
    function handle_sku_switch(sku_group) {
        if(sku_group){
            $("#edit-class .choose-sku-item").each(function(){
                var sku_attr_id = $(this).data('id')+'';
                if(sku_group.indexOf(sku_attr_id)!==-1){
                    $(this).addClass('cur')
                }
            })
        }else{
            var choose_sku_attr=[]
            $("#edit-class .cur").each(function(){
                var sku_attr_id = $(this).data('id')+'';
                choose_sku_attr.push(sku_attr_id)
            })
            //组装
            sku_group = choose_sku_attr.sort(sortNumber).join('|')

        }
        show_sku_info(sku_group)
    }

    //显示商品信息
    function show_sku_info(sku_group) {
        sku_group = '|'+sku_group+'|';
        if(sku_attr_info.hasOwnProperty(sku_group)){
            var info = sku_attr_info[sku_group]
            console.log(info)
            sku_id = info.sku_id;
            $("#price").text(info.price)
            $("#choose-sku em").text(info.name)
        }

    }

    function sortNumber(a,b)
    {
        return a - b
    }

</script>
<?php $this->endBlock()?>
