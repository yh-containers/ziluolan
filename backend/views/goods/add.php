<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','商品管理','商品操作'],
    ];
?>
<?php $this->beginBlock('style'); ?>
<style>
    #goods-img .item{position: relative; display: inline-block}
    #goods-img .item i{right: 0px;position: absolute;z-index: 999;font-size: 24px;color: red;cursor: pointer}
    #add-spu-block input{width: 160px;display: inline-block}
    #add-spu-block .fa-close{color: red}

    #sku-add-block .form-group{border-bottom: 1px solid #ccc;}
    #sku-add-block .form-group .control-label{text-align: left}
    #sku-add-block .form-group .control-label i{cursor: pointer}
    #sku-add-block .form-group .control-label i.fa-plus{color:#0a73bb ;margin-right: 3px}
    #sku-add-block .form-group .control-label i.fa-close{color:red}
    #sku-add-block .form-group .control-label em{float:right}

    #sku-block {}
    #sku-block .fa-close{color:red;cursor: pointer}
</style>
<?php $this->endBlock();?>

<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">商品操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品所属分类</label>

                <div class="col-sm-8">
                    <select class="form-control" name="n_id">
                        <option value="0">请选择商品分类</option>
                        <?php foreach ($nav as $vo){?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['n_id']?'selected':''?>><?=$vo['name']?></option>
                            <?php foreach ($vo['linkNavPage'] as $item){?>
                                <option value="<?=$item['id']?>" <?=$item['id']==$model['n_id']?'selected':''?>>&nbsp;&nbsp;&nbsp;&nbsp;┡━<?=$item['name']?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">名称</label>

                <div class="col-sm-8">
                    <input type="text" maxlength="150" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="名称">
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品图片</label>

                <div class="col-sm-10 margin-bottom">
                    <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'goods'])?>',data:{'<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->csrfToken ?>'}}" >上传文件</button>
                </div>
                <div class="col-sm-10 col-sm-offset-2" id="goods-img">
                    <?php $img = $model['image']?explode(',',$model['image']):[]; foreach ($img as $vo){?>
                        <div class="item">
                            <i class="fa fa-fw fa-close"></i>
                            <img src="<?=$vo?>" width="120" height="120"/>
                            <input type="hidden" name="image[]" value="<?=$vo?>"/>
                        </div>
                    <?php }?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">佣金模式</label>
                <div class="col-md-7">
                    <div class="radio">

                        <?php
                            $goods_mode = \common\models\Goods::getPropInfo('fields_mode');
                            if(!empty($goods_mode))
                                foreach ($goods_mode as $key=>$vo){
                        ?>
                        <label>
                            <input type="radio" name="mode"  value="<?=$key?>" <?= (empty($model)&& empty($key) )?'checked':($model['mode']==$key?'checked':'') ?>>
                            <?=$vo['name']?>
                        </label>
                        <?php }?>

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">销量</label>

                <div class="col-sm-8">
                    <input type="number" class="form-control" name="sold_num" value="<?= empty($model)?0:$model['sold_num']?>" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-8">
                    <input type="number" class="form-control" name="sort" value="<?= empty($model)?100:$model['sort']?>" placeholder="">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>

                <div class="col-sm-8">
                    <div class="radio">
                        <label>
                            <input type="radio" name="status"  value="1" <?= $model['status']!=2?'checked':'' ?>>
                            正常
                        </label>
                        <label>
                            <input type="radio" name="status" value="2" <?= $model['status']==2?'checked':'' ?>>
                            关闭
                        </label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-tab  layui-tab-brief" lay-filter="docDemoTabBrief">
                        <ul class="layui-tab-title">
                            <li class="layui-this">商品信息</li>
                            <li>商品规格</li>
                            <li>商品详细</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <div class="row">
                                    <div class="col-sm-4 " id="sku-add-block">
                                        <div class="form-group input-group input-group-sm">
                                            <input type="text" class="form-control" placeholder="请输入sku属性名">
                                            <span class="input-group-btn">
                                              <button type="button" class="btn btn-info btn-flat" id="sku-add">新增</button>
                                            </span>
                                        </div>

                                    </div>
                                    <div class="col-sm-8" id="sku-block">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="60">#</th>
                                                    <th width="250">组合名</th>
                                                    <th width="80">价格</th>
                                                    <th width="80">库存</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>


                            <div class="layui-tab-item">
                                <div class="form-group">
                                    <script id="attr" name="attr" type="text/plain"><?=$model['attr']?></script>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="form-group">
                                    <script id="container" name="content" type="text/plain"><?=$model['content']?></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="button" class="btn btn-info col-sm-offset-2 col-sm-8 col-xs-12" id="submit">保存</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
<div id="add-sku-attr-block" style="display: none">
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label" style="text-align: right">属性名</label>

        <div class="col-sm-8">
            <input type="text" name="attr_name" maxlength="15" class="form-control" value="" placeholder="">
        </div>
    </div>
</div>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('script');?>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.config.js"></script>
<script src="<?=\Yii::getAlias('@assets')?>/assets/ueditor1_4_3_3/ueditor.all.js"></script>

<script>
    //规格
    var ue_attr = UE.getEditor('attr');
    //详细资料
    var ue = UE.getEditor('container');
    $(function(){
        layui.use(['layer','upload','element'], function(){
            var layer = layui.layer;
            var upload = layui.upload;
            var element = layui.element;
            $.common.uploadFile(upload,'#test1',(res,item)=>{
                $("#goods-img").append('<div class="item">\n' +
                    '<i class="fa fa-fw fa-close"></i>\n' +
                    '<img src="'+res.path+'" width="120" height="120"/>\n' +
                    '<input type="hidden" name="image[]" value="'+res.path+'"/>'+
                    '</div>')
            })

            $("#sku-add").click(function(){
                var attr_name = $.trim($(this).parent().prev().val())
                if(!attr_name){
                    return false;
                }

                add_sku(attr_name);
            })


            $("#goods-img").on('click','.item i',function(){
                $(this).parent().remove()
            })

            //增加sku-attr
            $("#sku-add-block").on('click','i.fa-plus',function(){
                var $this = $(this)
                //数据
                var current_index = $this.parent().parent().index()-1
                var attr_name = $this.next().next().text()
                layer.open({
                    type:1
                    ,title:'添加属性('+attr_name+')'
                    ,btn:['确定','取消']
                    ,area:['500px','200px']
                    ,content:$('#add-sku-attr-block')
                    ,yes:function(index, layero){
                        var input_name = $.trim($("#add-sku-attr-block input[name='attr_name']").val())
                        if(input_name.length>0){
                            add_sku_attr(current_index,input_name)
                        }
                        layer.close(index)
                    }
                })
            })

            //删除
            $("#sku-add-block").on('click','.control-label>i.fa-close',function(){
                var current_index = $(this).parent().parent().index()-1
                $(this).parent().parent().remove()
                del_sku(current_index)
            })

            //删除具体sku-attr
            $("#sku-add-block").on('click','.item-min-attr i.fa-close',function(){
                //console.log('.item-min-attr>i.fa-close')
                // console.log(123)
                var index = $(this).parent().parent().parent().parent().index()-1;
                var attr_index = $(this).parent().parent().index();
                //console.log(index)
                //console.log(attr_index)
                $(this).parent().parent().remove()
                del_sku_attr(index,attr_index)
            })
            //修改文本属性
            $("#sku-block").on('change','tbody input',function(){
                var index = $(this).parents('tr').index()
                var name = $(this).data('name')
                var val = $(this).val()
                set_sku_table(index,name,val)
            })


            //删除
            $("#sku-block").on('click','tbody i.fa-close',function(){
                var index = $(this).parents('tr').index()
                $(this).parents('tr').remove()
                draw_table_del(index)
            })

            //提交数据
            $("#submit").click(function(){
                var req_obj = {};
                $("#form").serializeArray().map(function(item,index){
                    req_obj[item.name] = item.value
                })
                //绑定sku信息
                req_obj['sku'] = sku;

                //绑定sku价格信息
                req_obj['sku_table'] = sku_table;
                sendAjax({url:"",method:"post",data:req_obj})
            })

        });



    })

    //初始化sku
    var temp_id=0;
    var sku=<?=json_encode($sku,JSON_UNESCAPED_UNICODE)?>;
    //初始化table
    var sku_table=<?=json_encode($sku_table,JSON_UNESCAPED_UNICODE)?>;
    show_sku()
    draw_table_data()
    function show_sku(){
        //console.log(typeof sku)
        if(typeof sku==='object' && sku.length>0){
            var html='';
            sku.map(function(item,index){
                var attr = item.attr
                html +='<div class="form-group">\n' +
                    '<label  class="col-sm-3 control-label"><i class="fa fa-fw fa-plus"></i><i class="fa fa-fw fa-close"></i><em>'+item.name+'</em></label>\n' +
                    '<div class="col-sm-9">\n';
                attr.map(function(attr_item,attr_index){
                    html +='<div class="btn-group item-min-attr">\n' +
                        '<button type="button" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-close"></i></button>\n' +
                        '<button type="button" class="btn btn-xs btn-success">'+attr_item.name+'</button>\n' +
                        '</div>\n';
                })


                html+='</div>\n' +
                    '</div>';
            })
            $("#sku-add-block").append(html)
        }
    }

    //新增sku属性
    function add_sku(attr_name){
        temp_id--;
        //商品sku
        var sku_struct={
            temp_id:temp_id,
            name:attr_name,
            attr:[],
        };
        //页面新增数据
        $("#sku-add-block").append('<div class="form-group">\n' +
            '<label  class="col-sm-3 control-label"><i class="fa fa-fw fa-plus"></i><i class="fa fa-fw fa-close"></i><em>'+attr_name+'</em></label>\n' +
            '<div class="col-sm-9">\n' +
            '</div>\n' +
            '</div>');
        //插入数据
        sku.push(sku_struct);
        //重新绘制表格
        draw_table_data(1)
    }

    //新增sku属性
    function add_sku_attr(index,name){
        temp_id--;
        //强转
        index=index-0;
        console.log(index)
        if(!sku.hasOwnProperty(index)){
            return false;
        }

        sku[index].attr.push({
            temp_id:temp_id,
            name:name,
        })
        console.log(sku);

        //页面新增数据
        var opt_block = $("#sku-add-block>div").eq(index+1);

        opt_block.children('div').append('<div class="btn-group item-min-attr">\n' +
            '<button type="button" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-close"></i></button>\n' +
            '<button type="button" class="btn btn-xs btn-success">'+name+'</button>\n' +
            '</div>\n');

        //重新绘制表格
        draw_table_data(1)
    }

    //删除sku
    function del_sku(index) {
        if(!sku.hasOwnProperty(index)){
            return false;
        }
        sku.splice(index,1)
        //console.log(sku)

        draw_table_data(1)
    }
    //删除块属性
    function del_sku_attr(index,attr_index) {
        //验证索引是否存在
        if(!sku.hasOwnProperty(index) || !sku[index].hasOwnProperty('attr') || !sku[index].attr.hasOwnProperty(attr_index)){
            return false;
        }
        //console.log(sku)
        sku[index].attr.splice(attr_index,1)
        //console.log(sku)

        //重新绘制表格
        draw_table_data(1)
    }
    //table数据
    function draw_table_data(is_reset) {
        if(!sku_table.length || is_reset===1){
            if(typeof sku==='object' && sku.length>0){
                var sku_table_data = [];
                if(sku.length>1){
                    var attr_sku = [];
                    sku.map(function(item,index){
                        item.attr.length>0 && attr_sku.push(item.attr)
                    })
                    sku_table_data = calcDescartes(attr_sku)
                }else if(sku.length===1){

                    sku_table_data=sku[0]['attr']
                }

                //重构数据解构
                sku_table = [];
                sku_table_data.map(function(item,index){
                    sku_table.push({
                        'attr':Array.isArray(item)?item:[item],
                        'info':{}
                    })
                })
            }
        }
        console.log(sku_table)
        draw_table()
    }
    //绘制table数据
    function draw_table() {
        if(typeof sku_table==='object' && sku_table.length>0){
            var html = ''
            sku_table.map(function(item,index){
                var group_name = [];
                //属性信息
                var sku_table_attr = item.hasOwnProperty('attr')?item.attr:[];
                //info资料
                var sku_table_info = item.hasOwnProperty('info')?item.info:{};
                    sku_table_attr.map(function(gp_item,gp_index){
                    if(gp_item.hasOwnProperty('name')){
                        group_name.push(gp_item.name)
                    }
                })

                html+=' <tr>\n' +
                    '<td><i class="fa fa-fw fa-close"></i></td>\n'+
                    '<td>'+group_name.join('||')+'</td>\n' +
                    '<td><input type="number" class="form-control" data-name="price" value="'+(sku_table_info.hasOwnProperty('price')?sku_table_info.price:'')+'"/></td>\n' +
                    '<td><input type="number" class="form-control" data-name="stock" value="'+(sku_table_info.hasOwnProperty('stock')?sku_table_info.stock:'')+'"/></td>\n' +
                    '</tr>'
            })
            //清空数据
            //添加
            $("#sku-block table tbody").html('').append(html)
        }
    }
    //修改table属性
    function set_sku_table(index,name,val){
        if(sku_table.hasOwnProperty(index)){
            sku_table[index]['info'][name]=val
            console.log(sku_table[index])
            console.log(sku_table)
        }
    }
    //删除table数据
    function draw_table_del(index){
        sku_table.splice(index,1)
        console.log(sku_table)
    }

    function calcDescartes (array) {
        if (array.length < 2) return array[0] || [];
        return [].reduce.call(array, function (col, set) {
            var res = [];
            col.forEach(function (c) {
                set.forEach(function (s) {
                    var t = [].concat(Array.isArray(c) ? c : [c]);
                    t.push(s);
                    res.push(t);
                })
            });
            return res;
        });
    }

</script>
<?php $this->endBlock();?>

