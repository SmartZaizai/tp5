<?php

namespace app\admin\controller;


use think\Controller;
use Util\Sysdb;

class BaseAdmin extends Controller{

    public function __construct()
    {
        parent::__construct();
        //获取session
        $this->_admin = session('admin');

        if(!$this->_admin){
            header('Location:/index.php/admin/Login/login');
            exit();
        }

        //把模型对象实例赋值给模板变量，在模板中可以直接输出
        $this->assign('admin',$this->_admin);



        //判断是否有权限
        $this->db = new Sysdb;
        $group = $this->db->table('admin_groups')->where(array('gid'=>$this->_admin['gid']))->item();


        if(!$group){
            $this->request_error('对不起，您没有权限');
        }

        //权限

        $rights = json_decode($group['rights']);
        //dump($rights);

        //获取当前控制器；
        $controller = request()->controller();

        //获取档案控制器的方法
        $action = request()->action();

        $res = $this->db->table('admin_menus')->where(array('controller'=>$controller,'method'=>$action))->item();
        if(!$res){
            $this->request_error('对不起，您访问的功能不存在');
        }
        if($res['status']==1){
            $this->request_error('对不起，该功能已禁止使用');
        }
        if(!in_array($res['mid'],$rights)){
            $this->request_error('对不起，您没有权限');
        }




    }

    public function request_error($msg){
        //获取请求参数，是否为Ajax请求
        if(request()->isAjax()){
            exit(json_encode(array('code'=>1,'msg'=>$msg)));
        }
        exit($msg);
    }

}
