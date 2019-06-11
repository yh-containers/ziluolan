<?php

//用于显示左侧栏目选中状态
$this->params = [
    'crumb'          => ['系统管理','常规设置'],
];
?>
<?php $this->beginBlock('style'); ?>
<style>
    .textarea-block{position: relative;}
    .textarea-block i{right: 0;position: absolute;z-index: 999;font-size: 24px;color: red;cursor: pointer}
</style>
<?php $this->endBlock();?>
<?php $this->beginBlock('content'); ?>

<div class="row">
    <div class="container">
        <!-- 自定义菜单 -->
        <h3>自定义菜单</h3>
        <div class="custom-menu-edit-con">
            <div class="hbox">
                <div class="inner-left">
                    <div class="custom-menu-view-con">
                        <div class="custom-menu-view">
                            <div class="custom-menu-view__title">公众号名称</div>
                            <div class="custom-menu-view__body">
                                <div class="weixin-msg-list"><ul class="msg-con"></ul></div>
                            </div>
                            <div id="menuMain" class="custom-menu-view__footer">
                                <div class="custom-menu-view__footer__left"></div>
                                <div class="custom-menu-view__footer__right" ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inner-right">
                    <div class="cm-edit-after">
                        <div class="cm-edit-right-header b-b"><span id="cm-tit"></span> <a id="delMenu" class="pull-right" href="javascript:;">删除菜单</a></div>
                        <form class="form-horizontal wrapper-md" name="custom_form">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">菜单名称:</label><div class="col-sm-5">
                                    <input name="custom_input_title" type="text" class="form-control" ng-model="menuname" value="" placeholder="" ng-maxlength="5"></div><div class="col-sm-5 help-block">
                                    <div ng-show="custom_form.custom_input_title.$dirty&&custom_form.custom_input_title.$invalid-maxlength">字数不超过5个汉字或16个字符</div>
                                    <div class="font_sml" style="display: none;">若无二级菜单，可输入20个汉字或60个字符</div>
                                </div>
                            </div>
                            <div class="form-group" id="radioGroup">
                                <label class="col-sm-2 control-label">菜单内容:</label>
                                <div class="col-sm-10 LebelRadio">
                                    <label class="checkbox-inline"><input type="radio" name="radioBtn" value="sendmsg" checked> 发送消息</label>
                                    <label class="checkbox-inline"><input type="radio" name="radioBtn" value="link"> 跳转网页</label>
                                    <label class="checkbox-inline"><input type="radio" name="radioBtn" value="sendText"> 发送消息</label>
                                </div>
                            </div>
                        </form>

                        <div class="cm-edit-content-con" id="editMsg">
                            <div class="editTab">
                                <div class="editTab-heading">
                                    <ul class="msg-panel__tab">
                                        <li class="msg-tab_item on">
                                            <span class="msg-icon msg-icon-tuwen"></span>
                                            图文消息
                                        </li>
                                    </ul>
                                </div>
                                <div class="editTab-body">
                                    <div class="msg-panel__context">
                                        <div class="msg-context__item">
                                            <div class="msg-panel__center msg-panel_select"  data-toggle="modal" data-target="#selectModal"><span class="message-plus">+</span><br>从素材库中选择</div>
                                        </div>
                                        <div class="msg-template"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cm-edit-content-con" id="editPage">
                            <div class="cm-edit-page">
                                <div class="row">
                                    <label class="col-sm-6 control-label" style="text-align: left;">粉丝点击该菜单会跳转到以下链接:
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" style="text-align: left;">页面地址:
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" name="url" class="form-control" placeholder="认证号才可手动输入地址">
                                        <span class="help-block">必填,必须是正确的URL格式</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cm-edit-content-con" id="editText">
                            <div class="cm-edit-page">
                                <div class="row">
                                    <label class="col-sm-6 control-label" style="text-align: left;">粉丝点击该菜单会发送文本消息:
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" style="text-align: left;">发送模式:
                                    </label>
                                    <div class="col-sm-5">
                                        <label><input type="radio" name="mod" value="1" checked/>文字</label>
                                        <label><input type="radio" name="mod" value="2"/>图片</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" style="text-align: left;">消息内容:
                                    </label>
                                    <div class="col-sm-5">
                                        <textarea name="text" class="form-control" placeholder="认证号才可手动输入地址"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" style="text-align: left;">发送图片:
                                    </label>
                                    <div class="col-sm-5">

                                        <input type="hidden" name="media_id" value=""/>
                                        <input type="hidden" name="url" value=""/>
                                        <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?=\yii\helpers\Url::to(['upload/wechat'])?>',data:{'<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >上传文件</button>
                                        <img src="" alt="" class="radius" width="80" height="80">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cm-edit-before"><h5>点击左侧菜单进行操作</h5></div>
                </div>
            </div>
        </div>
        <div class="cm-edit-footer">
            <!--<button id="sortBtn" type="button" class="btn btn-default">菜单排序</button>-->
            <!--<button id="sortBtnc" type="button" class="btn btn-default">完成排序</button>-->
            <button id="saveBtns" type="button" class="btn btn-info1">保存</button>
        </div>
    </div>


    <div id="selectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>×</span></button>
                    <h4 class="modal-title">
                        选择图片
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php
                            foreach($material as $vo) {
                                $content = (isset($vo['content'])&&isset($vo['content']['news_item']))?$vo['content']['news_item'][0]:[];
                                if(!empty($content)){
                        ?>

                        <div id="col_<?=$vo['media_id']?>" class="col-xs-4">
                            <input type="hidden" name="media_id" value="<?=$vo['media_id']?>"/>
                            <div class="panel panel-default">
                                <div class="panel-heading msg-date">
                                    <?=date('Y年m月d日',$vo['update_time'])?>
                                </div>
                                <div class="panel-body">
                                    <h5 class="msg-title"><?=$content['title']?></h5>
                                    <div class="msg-img"><img src="<?=$content['thumb_url']?>" alt=""></div>
                                    <p class="msg-text"><?=$content['author']?></p>
                                </div>
                                <div class="mask-bg"><div class="mask-icon"><i class="icon-ok"></i></div></div>
                            </div>
                        </div>
                        <?php }}?>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info ensure">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    <div id="reminderModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>×</span></button>
                    <h4 class="modal-title">
                        温馨提示
                    </h4>
                </div>
                <div class="modal-body">
                    <h5>添加子菜单后，一级菜单的内容将被清除。确定添加子菜单？</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info reminder">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <div id="abnormalModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>×</span></button>
                    <h4 class="modal-title">
                        温馨提示
                    </h4>
                </div>
                <div class="modal-body">
                    <h5>数据异常</h5>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-info reminder">确定</button> -->
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->endBlock(); ?>
<?php $this->beginBlock('script'); ?>

<link rel="stylesheet" href="/admin/assets/wechat_assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/admin/assets/wechat_assets/css/font-awesome.min.css">
<!-- 自定义样式 -->
<link rel="stylesheet" href="/admin/assets/wechat_assets/css/wx-custom.css">

<!-- 自定义菜单排序 -->
<script src="/admin/assets/wechat_assets/js/Sortable.js"></script>
<script type="text/javascript" src="/admin/assets/handle.js"></script>

<script>
    var obj={
        "<?= Yii::$app->request->csrfParam ?>":"<?= Yii::$app->request->csrfToken ?>",
        "menu": {
            "button": <?=json_encode($var_menu,true)?>
        }
    };

    //Demo
    layui.use(['layer','upload'], function(){
        var layer = layui.layer;
        var upload = layui.upload;


        var uploadInst = upload.render({
            elem: '#test1' //绑定元素
            ,acceptMime:'image/*'
            ,done: function(res, index, upload){
                //获取当前触发上传的元素，一般用于 elem 绑定 class 的情况，注意：此乃 layui 2.1.0 新增
                var item = this.item;
                layer.msg(res.msg)
                if(res.code===1){
                    //触发input change事件
                    $("input[name='media_id']").trigger('input:changed', {media_id:res.media_id,path:res.path});

                    $(item).parent().find('img').attr('src',res.path)
                    $(item).prev().val(res.path)
                    $(item).prev().prev().val(res.media_id)
                }

            }
            ,error: function(){
                //请求异常回调
                layer.msg('上传异常')
            }
        });

    });

</script>
<script src="/admin/assets/wechat_assets/js/menu.js"></script>

<?php $this->endBlock(); ?>

