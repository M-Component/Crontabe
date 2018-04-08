<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Seven | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    {{stylesheet_link("public/backstage/bootstrap/css/bootstrap.min.css")}}
    <!-- Font Awesome -->
    {{stylesheet_link("public/backstage/awesome/css/font-awesome.min.css")}}
    <!-- Ionicons -->
    {{stylesheet_link("public/backstage/ionicons/css/ionicons.min.css")}}
    <!-- Theme style -->
    {{stylesheet_link("public/backstage/dist/css/AdminLTE.min.css")}}
    <!-- iCheck -->
    {{stylesheet_link("public/backstage/plugins/iCheck/square/blue.css")}}
    {{stylesheet_link("public/backstage/dist/css/AdminLTE.min.css")}}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    {{javascript_include("public/backstage/html5shiv/html5shiv.min.js")}}
    {{javascript_include("public/backstage/respond/1.4.2/respond.min.js")}}
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Admin</b>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{flash.output()}}</p>

        <form action="{{link_url('passport/login')}}" method="post">
            <div class="form-group has-feedback">
                {{text_field('username','class':'form-control','required':true,'placeholder':"账号")}}
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{password_field('password', 'class':'form-control','required':true,'placeholder':"密码")}}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{text_field('verify_code', 'class':'form-control','required':true,'placeholder':"验证码")}}
                <img src="{{link_url('passport/captcha')}}" title="点击更换验证码" style="position: absolute;right: 2px;top: 2px;height: 30px" onclick="this.src='{{link_url('passport/captcha')}}?d='+Math.random();">

            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> 记住登陆
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登陆</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <!-- /.social-auth-links -->

        <a href="#">忘记密码</a><br>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
{{javascript_include("public/backstage/plugins/jQuery/jquery-2.2.3.min.js")}}
<!-- Bootstrap 3.3.6 -->
{{javascript_include("public/backstage/bootstrap/js/bootstrap.min.js")}}
<!-- iCheck -->
{{javascript_include("public/backstage/plugins/iCheck/icheck.min.js")}}
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
