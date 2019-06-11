var layer
layui.use('layer', function(){
    layer=layui.layer
});
$.common={
    //发送验证码
    sendVerify(obj,type,point){
        var phone = point.val()
        var time=60
        if(!phone) {
            alert('请输入手机号码')
            return false;
        }
        if( typeof(type) != 'number') {
            alert('类型异常')
            return false;
        }
        $.post('/index/send-verify',{phone:phone,type:type},function(result){
            $(obj).attr('disabled',"true")
            alert(result.msg)
            var interval= setInterval(function(){
                if(time>0){
                    $(obj).text('请等待('+time+')')
                }else{
                    clearInterval(interval)
                    $(obj).text('获取验证码')
                    $(obj).removeAttr('disabled')
                }
                --time;
            },1000)
        })

    }
    //添加动作
    //添加动作
    ,reqInfo(obj,opt_info){
        opt_info = opt_info?opt_info:{}
        var ajax_obj = {
            url:'',
            type:'get',
            dataType:"json",
            data:{},
            beforeSend:function(){
                console.log('beforeSend')
                layer.load()
            },
            error:function(jsx){
                // console.log(jsx)
                layer.msg('请求异常')
            },
            success:function(res){
                console.log(res)
                layer.msg(res.msg)
            },
            complete:function(res,a,f){
                setTimeout(function(){layer.closeAll()},1000)
                if(res.status===200){
                    var responseText = res.responseText
                    if(responseText[0]==='{'){
                        responseText = eval('(' + responseText + ')');
                        if(responseText.hasOwnProperty('cart_num')){
                            //监听购物车数量变化
                            var cart_num_obj = $(".footer .item:eq(2)")
                            if(responseText.cart_num>0){
                                if(cart_num_obj.find('span').length){
                                    cart_num_obj.find('span').text(responseText.cart_num)
                                }else{
                                    cart_num_obj.find('a').append('<span>'+responseText.cart_num+'</span>')
                                }
                            }else{
                                cart_num_obj.find('span').remove()
                            }
                        }else if(responseText.hasOwnProperty('url')){
                            setTimeout(function(){window.location.href=responseText.url},1000)
                        }
                    }


                }

                //

            }

        }
        if(obj.hasOwnProperty('url')){
            conf = obj
        }else{
            var conf = $(obj).data('conf')
            conf = eval('(' + conf + ')');
        }

        var req_obj = Object.assign({},ajax_obj,conf)
        // console.log(req_obj)
        //发送ajax请求
        if(opt_info.hasOwnProperty('confirm_title')){
            layer.confirm(opt_info.confirm_title,function(){
                $.ajax(req_obj)
            })
        }else{
            $.ajax(req_obj)
        }

    }


}