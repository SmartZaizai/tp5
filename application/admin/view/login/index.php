<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登陆</title>
    <link rel="stylesheet" type="text/css" href="/static/plugins/layui/css/layui.css">
    <script type="text/javascript" src="/static/plugins/layui/layui.js"></script>
</head>
<body style="background: #1E9FFF">
    <div style="position:absolute;left: 50%;top:50%;width: 500px;margin-left: -250px;margin-top: -200px;">

        <div style="background:#FFFFFF;padding:20px;border-radius:4px;box-shadow: 5px 5px 20px #444444;">

            <div class="layui-form">

                <div class="layui-form-item" style="color: gray;">
                    <h2>劳动保障协理员管理系统</h2>
                </div>
                <hr>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" id="username" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">密&nbsp;&nbsp;&nbsp;&nbsp;码</label>
                    <div class="layui-input-block">
                        <input type="password" id="password" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">验证码</label>
                    <div class="layui-input-inline">
                        <input type="text" id="verifycode" class="layui-input">
                    </div>
                    <img src="{:captcha_src()}" id="img" onclick="reloadImg()">
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" onclick="dologin();return false;">登录</button>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script type="text/javascript">

        layui.use(['layer'],function(){
            $ = layui.jquery;
            layer = layui.layer;

            // 用户名控件获取焦点
            $('#username').focus();
            // 回车登录
            $('input').keydown(function(e){
                if(e.keyCode == 13){
                    dologin();
                }
            });
        });


        function reloadImg(){
            $('#img').attr('src','{:captcha_src()}?rand='+Math.random());
        }


        function dologin(){

            var username = $.trim($('#username').val());
            var pwd = $.trim($('#password').val());
            var verifycode = $.trim($('#verifycode').val());

            if(username == ''){
                layer.alert('请输入用户名',{icon:2})
                return;
            }

            if(pwd == ''){
                layer.alert('请输入密码',{icon:2});
                return;
            }

            if(verifycode == ''){
                layer.alert('请输入验证码',{icon:2});
                return;
            }

            $.post('/index.php/admin/Login/dologin',{'username':username,'pwd':pwd,'verifycode':verifycode},function(res){
                if(res.code>0){
                    reloadImg();
                    layer.alert(res.msg,{icon:2});
                }else{
                    layer.msg(res.msg);
                    setTimeout(function(){
                        window.location.href='/index.php/admin/Home/index '
                    },1000);
                }
            },'json');


        }
    </script>
</body>
</html>
