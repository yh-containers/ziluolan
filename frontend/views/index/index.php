<?php
$this->title = '紫罗兰花青素';
?>

<?php $this->beginBlock('content')?>

<div class="header">
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="content">
    <div class="flexslider">
        <ul class="slides">
            <volist name='banner' id="val">
                <li><img src="__PUBLIC__{$val.image}" /></li>
            </volist>
        </ul>
    </div>


    <div class="main clearfix">
        <div class="index_product">
            <div class="index_title">产品展示</div>
            <div class="index_product_content">
                <ul>
                    <volist name='index_pro' id="val">
                        <if condition="$val.id neq 1">
                            <li>
                                <div class="product_img"><a href="/m/view/{$val.id}.html"><img src="__PUBLIC__{$val.image}"></a></div>
                                <div class="product_text">
                                    <p class="product_name"><a href="/m/view/{$val.id}.html">{$val.name}</a></p>
                                    <?php if(null['price']>0){?>
                                        <?php if(null['id']==37 || null['id']==39){?>
                                            <p class="product_price">￥<?php echo null['price']*5;?>/5盒<a href=""><img src="/assets/wechat/images/car01.png"></a></p>
                                        <?php }elseif(null['id']==38){?>
                                            <p class="product_price">￥1980.00<a href=""><img src="/assets/wechat/images/car01.png"></a></p>
                                        <?php }else{?>
                                            <p class="product_price">￥<?php echo null['price'];?><a href=""><img src="/assets/wechat/images/car01.png"></a></p>
                                        <?php };?>
                                    <?php }else{?>
                                        <p class="product_price" >发售中</p>
                                    <?php };?>
                                </div>
                            </li>
                        </if>
                    </volist>
                </ul>
            </div>
        </div>
        <div class="index_advantage">
            <div class="index_title">公司简介</div>
            <div class="index_advantage_content">


                <p style="text-indent:2em; margin-bottom: 20px;"> 深圳市紫罗兰生物科技有限公司成立于2016年11月，自公司成立以来，一直致力于以科学技术为人类健康提供专业化的服务，是一家综合性高新技术企业。</p>
            </div>
        </div>

        <div class="index_video">
            <video controls="controls" width="100%" height="100%" poster="/assets/wechat/images/video.png">
                <source src="../video.mp4" type="video/mp4"></source>
            </video>
        </div>

        <div class="index_news">
            <div class="index_title">新闻资讯</div>
            <div class="index_news_content">
                <ul>
                    <foreach name="new_companynews_index" item="val">
                        <li>
                            <div class="news_left"><a href="/m/new/{$val.id}.html"><img src="__PUBLIC__{$val.image}"></a></div>
                            <div class="news_right">
                                <a href="/m/new/{$val.id}.html/">
                                    <h2>{$val.title}</h2>
                                    <p class="news_time">{$val.addtime|date="Y-m-d",###}</p>
                                    <p class="news_text">{$val.desc}</p>
                                </a>
                            </div>
                        </li>
                    </foreach>

                </ul>
            </div>
        </div>
    </div>


    <!-- 底部-->
    <!-- 底部-->
    <div class="clearfix" style="height:100px;"> </div>
    <div class="boom_kf">
        <a class="b_kf_index cur" href="/m"><i></i>
            <span>首页</span></a>
        <a class="b_kf_feek cur"  href="/m/product/hqsgtyl.html"><i></i>
            <span>分类</span></a>
        <a class="b_kf_phone cur" href="/m/cart.html"><i></i>
            <span>购物车</span></a>
        <a class="b_kf_qq cur" href="/m/member.html"><i></i>
            <span>个人中心</span></a>
    </div>
</div>
<?php $this->endBlock()?>
