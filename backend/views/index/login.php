<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?=\Yii::$app->name?></title>

    <!-- Tell the browser to be responsive to screen width -->

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->

    <link rel="stylesheet" href="/admin/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="/admin/assets/bower_components/font-awesome/css/font-awesome.min.css">

    <!-- Ionicons -->

    <link rel="stylesheet" href="/admin/assets/bower_components/Ionicons/css/ionicons.min.css">

    <!-- Theme style -->

    <link rel="stylesheet" href="/admin/assets/dist/css/AdminLTE.min.css">

    <!-- iCheck -->

    <link rel="stylesheet" href="/admin/assets/bower_components/iCheck/square/blue.css">

    <style type="text/css">

        .w1200 {width: 1200px;margin: auto;position: relative;}

        .header {width: 100%;padding: 10px 0px;}

        .header a b {font-size: 40px;color: #333;}

        .content {width: 100%;height: 620px;background: url(https://www.szhulian.com/Public/admin/images/bg.jpg) no-repeat center top;}

        .login-box-warp {float: right;padding: 15px;background-color: rgba(255,255,255,0.5);margin-top: 100px;}

        .login-box-body {width: 400px;background-color: #fff;padding:30px 20px;}

        .login-box-body h2 {color: #f39430;font-size: 21px;text-align: center;font-weight: normal;margin:0px 0px 30px;}

        .login-box-body h2 span {color: #999;font-size: 18px;font-weight: normal;}

        .login-box-body .form-control {height: 40px;font-size: 15px;border-radius: 3px;}

        .login-box-body .form-group {min-height: 40px;width: 100%}

        .login-box-body .form-group img {float: right;width: 130px;height: 40px;cursor: pointer;border:1px solid #d2d6de;border-radius: 3px;overflow: hidden;border-radius: 3px;}

        .login-box-body .form-group .verify {width: 220px;float: left;}

        .login-box-body .btn {height: 45px;font-size: 16px;border-radius: 3px;}

        .box {margin: 0px;border-top:0px;}

        @media (max-width:767px) {

            .w1200 {width: 100%;}

            .header {width: 100%;padding: 10px 15px;}

            .header a b {font-size: 24px;}

            .content {height: calc(100vh - 54px);}

            .login-box-warp {float: none;margin: 0px;padding: 75px 15px 0px;background-color:transparent;}

            .login-box-body {width: 100%;box-sizing: border-box;}

            .login-box-body .form-group .verify {width: calc(100% - 120px);}

            .login-box-body .form-group img {width: 110px;}

        }



    </style>

</head>

<body>

<div class="box">

    <div class="header">

        <div class="w1200">

            <a href="javascript:;"><b><?=\Yii::$app->name?>后台管理</b></a>

        </div>

    </div>

    <!-- /.login-logo -->

    <div class="content">

        <div class="content-layout w1200">

            <div class="login-box-warp">

                <div class="login-box-body">

                    <h2>账号登录 <span>UserLogin</span></h2>

                    <form action="" method="post" id="form">

                        <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                        <div class="form-group">

                            <input type="text" name="account" class="form-control" placeholder="账号">

                        </div>

                        <div class="form-group">

                            <input type="password" name="password" class="form-control" placeholder="密码">

                        </div>

                        <div class="form-group">

                            <input type="text" name="verify" class="form-control verify" placeholder="验证码">

                            <img src="<?= \yii\helpers\Url::to(['captcha'])?>" id="captcha-image" onclick="changeImage(this)" class="captcha"/>

                        </div>



                        <div class="row" style="margin-top: 25px;">

                            <!-- /.col -->

                            <div class="col-xs-12">

                                <button type="button" id="submit" class="btn btn-primary btn-block btn-flat">登录</button>

                            </div>

                            <!-- /.col -->

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <!-- /.login-box-body -->

</div>

<!-- /.login-box -->



<!-- jQuery 3 -->

<script src="/admin/assets/bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->

<script src="/admin/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script>

    $(function () {

        $("#submit").click(function () {

            $.post($("#form").attr('action'),$("#form").serialize(),function(result){

                if(result.hasOwnProperty('url')){

                    window.location.href = result.url

                }else{

                    changeImage()

                    alert(result.msg)

                }

            })



        })

    });



    function changeImage() {

        var url = "<?= \yii\helpers\Url::to(['captcha'])?>";

        console.log(url.indexOf('?'));

        if(url.indexOf('?')>-1){

            url = url+'&m='+Math.random()

        }else{

            url = url+'?m='+Math.random()

        }

        $("#captcha-image").attr('src',url);

    }

</script>

</body>

</html>

