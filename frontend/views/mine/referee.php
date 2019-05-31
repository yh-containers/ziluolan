<?php
$this->title = '推荐人';
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
<div class="content order ">


    <!-- 业务范围-->
    <div class="class-nav clearfix">
        <ul class="clearfix wrap">
            <li style="width: 25%;" class="left fl cur"><a href="javascript:;">直接推荐人</a></li>

        </ul>
    </div>
    <?php if(!empty($mine_up))  {?>
    <div class="pick">
        <div class="wrap clearfix">
            <ul class="clearfix">
                <li class="wow fadeInDown animate clearfix">
                    <div class="img">

                        <div class="content fl" style="width: 100%;">
                            <span class=" fl">用户：<?=$mine_up['username']?></span>
                            <span class=" fr">时间：<?=$mine_up['create_time']?date('Y-m-d H:i:s',$mine_up['create_time']):''?></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <?php }?>

    <div class="class-nav clearfix" style="margin-top: 10px;">
        <ul class="clearfix wrap">
            <li style="width: 25%;" class="left fl cur"><a href="javascript:;">节点推荐人</a></li>

        </ul>
    </div>
    <div class="pick">
        <div class="wrap clearfix">
            <ul class="clearfix">
                <foreach name="data" item="val">
                    <?php if(!empty($link_users)) foreach($link_users as $vo) {?>
                    <li class="wow fadeInDown animate clearfix">
                        <div class="img">

                            <div class="content fl" style="width: 100%;">
                                <span class=" fl">用户：<?=$vo['username']?></span>
                                <span class=" fr">时间：<?=$vo['create_time']?date('Y-m-d H:i:s',$vo['create_time']):''?></span>

                            </div>
                        </div>
                    </li>
                    <?php }?>
                </foreach>
            </ul>
        </div>
    </div>
</div>



<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>


</script>
<?php $this->endBlock()?>

