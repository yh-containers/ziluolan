<?php
$this->title = $model['name'];

?>

<?php $this->beginBlock('content')?>

<body style="background:#eee;">
<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="__PUBLIC__/m/images/back.png" alt=""></a>
    <div class="logo">商品详情</div>
</div>
<div class="content pro">
    <div class="flexslider">
        <ul class="slides">

            <?php
                $images = empty($model['images'])?[]:$model['images'];
                foreach ($images as $vo){
            ?>
                <li><img src="<?=$vo?>" /></li>
            <?php }?>
        </ul>
    </div>
    <!-- banner图JS -->
    <script>

    </script>

    <!-- 业务范围-->
    <div class="title bj1">
        <div class=" wrap">
            <div class="title1">{$model['name']}</div>


            <div class="title2">单价:<span class="span1">{$data.price}</span></div>
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
        <a href="/m/cart.html">
            <div class="icon fl"></div>
        </a>
        <a  href="javascript:void(0)" onclick="onshowcart()">
            <div class="gouwuche fl" onclick="" id="gouwu">加入购物车</div>
        </a>
        <a href="javascript:;" onclick="addVlaue('settlement',1)"><div class="goumai fr">立即购买</div></a>

    </div>
</div>

<!-- 产品选择 -->
<div class="edit-class" id="edit-class" <?php if($_GET['tid']==1){ ?>style="display:block"<?php };?>>
    <div class="xiala clearfix" style="display: block;"><strong><a href="javascript:void(0)" onclick="onhiencart()"><i class="fr guanbi"></i></a></strong>
        <div class="wrap">
            <div class="title clearfix">{$data.name}</div>
            <div class="content clearfix">
                <div class="img fl"><img src="__PUBLIC__{$data.image}"></div>
                <div class="right fl">
                    <if condition="in_array($data['id'],array('34','29','30','1'))">
                        <div class="price">￥<?php echo $data[price]*2; ?></div>
                        <else />
                        <div class="price">￥<?php echo $data[price]*1; ?></div>
                    </if>


                    <input type="hidden" id="price" value="{$data.price}" /><br />
                    <if condition="in_array($data['id'],array('34','29','30','1'))">
                        <p>*快递费15元/2盒</p>
                        <elseif condition="$data['id'] eq 33" />
                        <p>*免邮费</p>
                        <elseif condition="$data['id'] eq 32" />
                        <p>*快递费5元/盒</p>
                    </if>

                    <p>*下单请备注购买需求</p>
                </div>
            </div>
            <input type="hidden" value="{$data.name}" class="data_name">
            <div class="color-class clearfix">
                <p>产品备注</p>
                <div class="size" style="border-bottom:none;">
                    <foreach name="pro_name" item="val" key="ke">
                        <span class="freight-info <?php if($data['id']==$val['id']){ ?>cur<?php };?>" <?php if($ke != 0){ ?>style="margin-top:10px" <?php };?> ><a <?php if($data['id']==$val['id']){ ?>style="color:#fff"<?php };?> href="/m/view/{$val.id}.html?tid=1">{$val.name}</a></span>
                    </foreach>

                </div>
            </div>
            <div class="color-class clearfix">
                <p>请选择规格</p>
                <div class="size" style="border-bottom:none;">

                    <if condition="in_array($data['id'],array('34','29','30','1'))">
                        <input type="hidden" name="guige" id="guige" value="2">
                        <else />
                        <input type="hidden" name="guige" id="guige" value="1">
                    </if>




                    <if condition="in_array($data['id'],array('34','29','30','1'))">
                        <span class="freight-info cur" onclick="onGguige(this,2)">2<?php if ($data['cid']==10 or $data['cid']==26){echo '盒';}else{echo '瓶';} ?></span>
                        <else />
                        <span class="freight-info cur" onclick="onGguige(this,1)">1<?php if ($data['cid']==10 or $data['cid']==26){echo '盒';}else{echo '瓶';} ?></span>
                    </if>

                    <if condition="$data['id'] EQ 37">
                        <span class="freight-info " onclick="onGguige(this,5)">5<?php if ($data['cid']==10 or $data['cid']==26){echo '盒';}else{echo '瓶';} ?></span>
                        <else />
                        <span class="freight-info" onclick="onGguige(this,100)">100<?php if($data['cid']==10 or $data['cid']==26){echo '盒';}else{echo '瓶';} ?></span>
                        <span class="freight-info" onclick="onGguige(this,300)">300<?php if($data['cid']==10 or $data['cid']==26){echo '盒';}else{echo '瓶';} ?></span>
                    </if>


                </div>
                <div class="size" style="border-bottom:none;">
                    <if condition="$data['id'] EQ 32">
                        <input type="hidden" name="type" id="type" value="绿盒装(排毒瘦身)">
                        <span class="freight-info cre" onclick="onGtype(this,'绿盒装(排毒瘦身)')">绿盒装(排毒瘦身)</span>
                        <span class="freight-info " onclick="onGtype(this,'黄盒装(调节三高)')">黄盒装(调节三高)</span>
                        <span class="freight-info " onclick="onGtype(this,'黑盒装(助睡眠)')">黑盒装(助睡眠)</span>
                        <span class="freight-info " onclick="onGtype(this,'白盒装(补肾)')">白盒装(补肾)</span>
                        <else />
                        <input type="hidden" name="type" id="type" value="无">
                    </if>

                </div>
            </div>
            <div class="color-class clearfix" style=" padding-bottom:20px">
                <p>


                    <if condition="in_array($data['id'],array('34','29','30','1'))">
                        <span class="fl">购买数量/份</span><span class="fl heshu" style="margin-left:10px;color:red">已选择：2盒</span>
                        <else />
                        <span class="fl">购买数量/份</span><span class="fl heshu" style="margin-left:10px;color:red">已选择：1盒</span>
                    </if>

                <div class="tianjia fr">
                    <div class="shop-arithmetic">
                        <a href="javascript:;" class="" onclick="jian(this)" style="position:relative;top:0px;overflow:hidden;border-right:1px solid #e0e0e0;">-</a>
                        <span class="num"  class="num_input" id="jian_sun" style="line-height:23px;top:0px;overflow:hidden;left:0;width:30px;">1</span>
                        <a href="javascript:;" onclick="addp(this)" class="" style="position:relative;top:0px;overflow:hidden;border-left:1px solid #e0e0e0;left: -1px;">+</a>
                    </div>
                </div>
                </p>
            </div>
        </div>
    </div>
</div>

<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script src="<?=\Yii::getAlias('@assets')?>/js/jquery.flexslider-min.js"></script>
<script>
    $(function(){
        jQuery(".slideTxtBox").slide({easing:"easeOutCirc"});
        $('.flexslider').flexslider({
            directionNav: true,
            pauseOnAction: false
        });

    });
</script>
<?php $this->endBlock()?>
