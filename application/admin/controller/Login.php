<?php

namespace app\admin\controller;

use think\Controller;
use Util\Sysdb;

class Login extends Controller{

    public function index(){
        return $this->fetch();
    }


    public function dologin(){

        $username = trim(input('post.username'));
        $pwd = trim(input('post.pwd'));
        $verifycode = trim(input('post.verifycode'));

        if($username == '1'){
            exit(json_encode(array('code'=>1,'msg'=>'用户名不能为空')));
        }
        if($pwd == ''){
            exit(json_encode(array('code'=>1,'msg'=>'密码不能为空')));
        }
        if($verifycode == ''){
            exit(json_encode(array('code'=>1,'msg'=>'请输入验证码')));
        }

        if(!captcha_check($verifycode)){
            exit(json_encode(array('code'=>1,'msg'=>'验证码错误')));
        }



        $this->db = new Sysdb;

        //根据用户名查询信息--如果有数据，再对密码进行验证，减少一次数据库查询操作
        $admins = $this->db->table('admins')->where(array('username'=>$username))->item();

        if(!$admins){
            exit(json_encode(array('code'=>1,'msg'=>'用户名不存在')));
        }

        if(md5($admins['username'].$pwd)!=$admins['password']){
            exit(json_encode(array('code'=>1,'msg'=>'密码错误')));
        }

        if($admins['status']==1){
            exit(json_encode(array('code'=>1,'msg'=>'用户已经被禁用')));
        }

        session('admin',$admins);
        exit(json_encode(array('code'=>0,'msg'=>'登陆成功')));

    }

    public function logout(){
        session('admin',null);
        exit(json_encode(array('code'=>0,'msg'=>'退出成功')));
    }

}