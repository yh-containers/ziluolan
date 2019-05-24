<?php
$this->title = '账户余额';
$this->params = [
];
?>

<?php $this->beginBlock('style')?>

<style>

</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>

<div class="header"> <a href="javascript:window.history.back()" class="back"><img src="<?=\Yii::getAlias('@assets')?>/images/back.png" alt=""></a>
    <div class="logo"><?=$this->title?></div>
    <a href="javascript:;" class="sort cl_nav"></a>
</div>

<div class="clearfix" style="height:60px;"> </div>
<div class="content  sub-order bj1">
    <!-- -->
    <div class="bank clearfix">
        <if condition="$member.tstate neq 1">
            <form action="" class="withdraw-from">
                <label class="clearfix">
                    <div class="jine clearfix fl">金豆/押金</div>
                    <input class="fr txt wallet"  type="text" placeholder="请填写数量"/>
                </label>
            </form>
        </if>
        <ul class=" wrap">

            <li class="clearfix"><span class="fl"><i class="left icon3"></i>我的金豆：<i class="price" index="{$member.wallet}">
      <if condition="$member.txtype eq 1">
      <?php //echo $member['wallet'];?>
      <else/>
      <?php //echo $member['wallet']-$member['tprice'];?>
      </if>
      </i></span></li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>消费金豆：<i style="color:#f14141;padding-left:20px;"><?php //echo $member['consum_wallet'];?></i></span></li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>   健康豆 ：<i style="color:#f14141;padding-left:20px;"><?php //echo $member['deposit_money'];?></i></span></li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>可退押金：<i class="back_deposit" index="{$member.back_deposit}" style="color:#f14141;padding-left:20px;">
      <if condition="$member.txtype neq 1">
      <?php //echo $member['back_deposit'];?>
      <else/>
      <?php //echo $member['back_deposit']-$member['tprice'];?>
      </if>

      </i></span></li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>团队业绩：<i style="color:#f14141;padding-left:20px;"><?php //echo $Tmongey;?></i></span></li>
            <li class="clearfix"><span class="fl"><i class="left icon3"></i>团队提成：<i style="color:#f14141;padding-left:20px;"><?php //echo $member['team_wallet'];?></i></span></li>
        </ul>
    </div>
    <if condition="$member.tstate eq 1">



        <label class="clearfix">
            <div class="jine clearfix" style="text-align:center;color:red;margin-top:2%;">提现中*** （{$member.tprice}）</div>
        </label>


    </if>
    <if condition="$member.tstate neq 1">
        <p style="color: red;margin:0px;text-align: center;">金豆兑换需收取5%手续费</p>
        <p style="color: red;margin:0px;text-align: center;">金豆提现10%自动兑换消费金豆</p>
        <p style="color: red;margin:0px;text-align: center;">申请兑换后三个工作日内到账</p>
        <div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix"  onclick="tj()">金豆提现</div>

    </if>
    <div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix"  onclick="yajin()">押金提现</div>
    <div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix"  onclick="showzuanzhang(this)">金豆赠送</div>
    <!--银兑换金 20171213-->
    <div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix" onclick="sgbans()">银豆兑换金豆</div>
    <!--银兑换金end 20171213-->
    <div style="margin-top: 4%;margin-bottom: 4%" class="withdraw clearfix" onclick="health()">健康豆兑换金豆</div>
    <a href="/m/pricestate.html"><div style="margin-top:4%;margin-bottom:4%;background:#828181;" class="withdraw clearfix">查看明细</div></a>
</div>



<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>


</script>
<?php $this->endBlock()?>

