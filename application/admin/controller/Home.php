<?php

namespace app\admin\controller;

use think\Controller;
use Util\Sysdb;

class Home extends BaseAdmin {

    public function index(){

        $menu = false;
        $this->db = new Sysdb;

        //1.根据用户gid获取 用户分组数据
        $role = $this->db->table('admin_groups')->where(array('gid'=>$this->_admin['gid']))->item();


        //2.获取用户权限列表
        if($role){
            $role['rights'] = (isset($role['rights']) && $role['rights'])? json_decode($role['rights'],true) :[];
        }

        //3.根据权限列表获取可访问菜单
        if($role['rights']){
            $where = 'mid in('.implode(',',$role['rights']).') and ishidden=0 and status=0';
            $menus = $this->db->table('admin_menus')->where($where)->cates('mid');
            $menus && $menus=$this->gettreeitems($menus);

        }

        $site = $this->db->table('sites')->where(array('names'=>'site'))->item();
        $site && $site['values']=json_decode($site['values']);

        $this->assign('site',$site);
        $this->assign('role',$role);
        $this->assign('menus',$menus);

        return $this->fetch();
    }


    private function gettreeitems($items){
        $tree = array();
        foreach ($items as $item) {

            if(isset($items[$item['pid']])){
                $items[$item['pid']]['children'][] = &$items[$item['mid']];
            }else{
                $tree[] = &$items[$item['mid']];
            }
        }
        return $tree;
    }

    public function welcome(){
        return $this->fetch();
    }

}