<?php
$this->title = '订单提交详情';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>
<style>
    .invoice.slideTxtBox{margin:auto auto 100px;padding:0 3%;box-sizing:border-box;width:100%;text-align:left;clear:both;}
    .invoice.slideTxtBox .hd{height:30px;line-height:30px;position:relative;}
    .invoice.slideTxtBox .hd ul{margin-top:25px;}
    .invoice.slideTxtBox .hd ul li{float:left;text-align:center;padding:0 15px;width:33.333%;cursor:pointer;border:1px solid #ccc;box-sizing:border-box;margin-left:-1px;height:32px;line-height:32px;}
    .invoice.slideTxtBox .hd ul li.on{border-color:#7d1f88;background:#7d1f88;color:#fff;}
    .invoice.slideTxtBox .bd ul{padding:15px 0;zoom:1;color:#999;font-size:12px;}
    .choose,.add{height:100%;padding-top:20px;}
    .choose ul li {border:none;}
    .choose ul li  input[type="text"] {margin-bottom: -1px;padding:10px;border:1px solid #ccc;box-sizing:border-box;width:100%;}
    .message-div {padding-top: 10px;}
    .message-div textarea {width: 100%;border:1px solid #dcdcdc;height: 90px;padding: 5px;border-radius: 5px;margin-top: 5px;box-sizing:border-box;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>


<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<form action="/m/settlement.html" id="settlement" method="post">
    <div class="content sub-order bj1">
        <div class="daishou clearfix">
            <a class="cur" id="tihuo" href="javascript:;" onclick="tihuoshow(this)"><i class="on"></i>快递</a>
            <a class="rucang" href="javascript:;" onclick="tihuohide(this)"><i class="on"></i>自提</a>
        </div>
        <div class="Personal address clearfix" id="address">
            <div class="list clearfix">
                <ul>
                    <?php if(!empty($addre)){ ?>
                        <volist name="addre" id="val">
                            <li class="clearfix" >
                                <div class="left fl" onclick="setaddre(this,{$val.id})">
                                    <p><span>{$val.name}</span><span>{$val.phone}</span>
                                        <?php if($val['type']==1){?><span style="color: #E8641D">默认收货地址</span>
                                        <?php }else{ ?>设为默认收货地址<?php } ?>
                                    </p>
                                    <p>{$val.province}{$val.city}{$val.district}{$val.detail}</p>
                                </div>
                                <a href="/m/address.html?cart_id=">
                                    <div class="right1 fr"></div>
                                </a>
                            </li>
                        </volist>
                    <?php }else{ ?>
                        <li class="clearfix" >
                            <a href="/m/address.html?cart_id=" class="addr_no">你还没有默认收货地址，点击去创建</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <script>

        </script>
        <volist name="pro" id="val">
            <div class="pick">
                <div class="wrap clearfix">
                    <ul class="clearfix">

                        <li class="wow fadeInDown animate clearfix">
                            <div class="img">
                                <div class="pic fl"><a href="javascript:;"><img class="tran" src="__PUBLIC__{$val.pro.image}"></a></div>
                                <div class="content fl">
                                    <p class="title"><a href="javascript:;">{$val.pro.name}【{$val.cart.guige} 】* {$val.cart.num}份</a></p>
                                    <p class="price">单价:¥ {$val.cart.price}&nbsp;&nbsp; 总价:￥ </p>
                                    <p class="quantity">折扣：0</p>
                                    <p class="quantity">数量：0</p>
                                    <p class="quantity">备注：0</p>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="message-div">
                <div class="wrap clearfix">
                    <ul class="clearfix">
                        <li>

                            <if condition="in_array($val.pro.id,array('34','29','30'))">
                                <span style="color: #7c1f87">请注明花青素酒和固体饮料的各自数量</span>
                                <else />
                                <span style="color: #7c1f87">备注:</span>
                            </if>

                        </li>
                        <li>
                            <textarea name="message" id="" cols="30" rows="10"></textarea>
                        </li>
                    </ul>
                </div>
            </div>
        </volist>
        <div class="slideTxtBox invoice clearfix">
            <div class="hd clearfix">
                <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
                <span class="arrow"><a class="next"></a><a class="prev"></a></span>
                <ul>
                    <li class="on" onclick="setfapiao(0)">不开发票</li>
                    <li onclick="setfapiao(1)">普通发票</li>
                    <li onclick="setfapiao(2)">增值发票</li>
                </ul>
            </div>
            <input type="hidden" name="fapiao" value="0" id="fapiao"/>
            <script>
                function setfapiao(val){
                    $('#fapiao').val(val);
                }
            </script>
            <div class="bd clearfix">
                <ul>
                </ul>
                <ul>
                    <p>所有发票运费由客户自己承担</p>
                    <p>以下发票提示只适用于购买特别定制酒的客户。 </p>
                    <p>鉴于增值税的层层抵扣特征，作为《代销服务协议》中的委托人， </p>
                    <p>您有义务给我们开具增值税专用发票，由于您未履行此义务，故我 </p>
                    <p>们只给您开非代售部分的发票，并且以商业折扣的形式开具，发票 </p>
                    <p>将在货物到达之日起 </p>
                    <p>一个月内采用到付的形式寄出。</p>
                </ul>
                <ul>
                    <p>所有发票运费由客户自己承担</p>
                    <p>以下发票提示只适用于购买特别定制酒的客户。 </p>
                    <p>鉴于增值税的层层抵扣特征，作为《代销服务协议》中的委托人， </p>
                    <p>您有义务给我们开具增值税专用发票，由于您未履行此义务，故我 </p>
                    <p>们只给您开非代售部分的发票，并且以商业折扣的形式开具，发票 </p>
                    <p>将在货物到达之日起 </p>
                    <p>一个月内采用到付的形式寄出。</p>
                    <div class="choose">
                        <ul class="add">
                            <li>
                                <input type="text" name="companyname" placeholder="*单位名称：">
                            </li>
                            <li>
                                <input type="text" name="taxpayernumber" placeholder="*纳税人识别号：">
                            </li>
                            <li>
                                <input type="text" name="registeaddress" placeholder="*注册地址：">
                            </li>
                            <li>
                                <input type="text" name="registetelephone" placeholder="*注册电话：">
                            </li>
                            <li>
                                <input type="text" name="bank" placeholder="*开户银行：">
                            </li>
                            <li>
                                <input type="text" name="bankaccount" placeholder="*银行账号：">
                            </li>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
        <script type="text/javascript">jQuery(".slideTxtBox").slide();</script>

    </div>
    <input type="hidden" name="cart_id" value="{$cart_id}">
    <input type="hidden" id="kdi_price" value="{$kdi_price}">
    <input type="hidden" name="tihuo_type" id="tihuo_type" value="0"/>
</form>
<!-- 底部-->
<!-- 底部-->
<div class="footer-sub-order pro-footer clearfix">
    <div class="fl rmb"><span>需支付：</span><span class="span1">￥{$zong_price}(包含运费)</span></div>
    <input type="hidden" id="pro_price" value="{$pro_price}" />
    <div class="fr tjdd" id="tjdd"><a href="javascript:;" style="color:#fff;" onclick="tj()">提交订单</a></div>
    <div class="fr tjdd" id="tjdd_hide" style=" display:none;"><a href="javascript:;" style="color:#fff;" >正在提交</a></div>
</div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/js/jquery.flexslider-min.js"></script>
<script>

    jQuery(".slideTxtBox").slide();
    function tihuoshow(_this){
        var type = $('#tihuo_type').val();
        if(type==1){
            $('#tihuo_type').val('0');
            var kdi_price = Number($('#kdi_price').val());
            var pro_price = Number($('#pro_price').val());

            var sun = kdi_price+pro_price;
            $('.span1').html('￥'+parseFloat(sun).toFixed(0)+'(包含运费)');
            $("#address").slideToggle('show');
            $(_this).addClass("cur").siblings().removeClass("cur");
        }
    }
    function tihuohide(_this){
        var type = $('#tihuo_type').val();
        if(type==0){
            $('#tihuo_type').val('1');
            var pro_price = $('#pro_price').val();
            $('.span1').html('￥'+parseFloat(pro_price).toFixed(0)+'(不含运费)');
            $("#address").slideUp('show');
            $(_this).addClass("cur").siblings().removeClass("cur");
        }
    }

</script>
<?php $this->endBlock()?>

