<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 2018/5/2
 * Time: 9:12
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Cookie;
use think\Session;
use app\index\model\admin;
use app\index\model\leader;
use think\Db;
use think\Request;
class offices extends Base{
    public function officelist(){
        $data=db("office")->select();
        $this->assign("data",$data);
        return $this->fetch();
        //
    }

    public function addofficer(){
        return $this->fetch();
    }

    public function add(){
        $username = $this->request->post('username');
        $wid = $this->request->post('wid');
        $data=['username'=>$username,'wid'=>$wid];
        $res=Db::table('office')->insert($data);
        if($res)
            $this->redirect('offices/officelist');
        else
            $this->redirect('addofficer');
    }
}