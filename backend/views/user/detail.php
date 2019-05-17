<?php
$this->params=[
        'current_active'=>['user','user/index']
];
?>
<?php $this->beginBlock('style')?>

<style type="text/css">
    #foonav{ display:none !important;}
    #foonav2{ display:none !important;}

</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">用户:<?=$model['username']?> 会员号:<?=$model['number']?></h3>
        <a href='<?=\yii\helpers\Url::to(['index'])?>' class="btn btn-primary" onclick="">返回</a>
    </div>
    <div class="box-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab">详细信息</a></li>

        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="home">
                <div class="box-body">
                    <table id="example" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="5%">真实姓名</th>
                            <th width="5%">性别</th>
                            <th width="5%">生日</th>
                            <th width="10%">城市</th>
                            <th width="5%">微信号</th>
                            <th width="5%">手机号</th>
                            <th width="5%">身份证</th>
                            <th width="10%">注册时间</th>
                            <th width="10%">最近登录</th>
                            <th width="5%">购买数</th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <th><?=$model['username']?></th>
                            <th><?=\common\models\Member::getSex($model['sex'])?></th>
                            <th><?=$model['username']?></th>
                            <th><?=$model['username']?></th>
                            <th><?=$model['username']?></th>
                            <th><?=$model['username']?></th>
                            <th><?=empty($model['idcard'])?'未上传':'<a href="'.$model['idcard'].'" class="label label-primary" target="_blank">点击查看正面</a>'?></th>
                            <th><?=$model['dl_addtime']?date('Y-m-d H:i',$model['addtime']):''?></th>
                            <th><?=$model['dl_addtime']?date('Y-m-d H:i',$model['dl_addtime']):''?></th>
                            <td><?=$model['integral']?></td>


                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>





<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>

    function partner(id,partner){
        $("#id").val(id);
        $("#sp_name").val(partner);
        $("#expre").addClass('show1');
    }
    function t_fh_off(_this){
        $("#expre").removeClass('show1');
    }

</script>
<?php $this->endBlock()?>
