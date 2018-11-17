<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 2018/5/2
 * Time: 8:11
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Cookie;
use think\Session;
use app\index\model\admin;
use think\Db;
use think\Request;

class home extends Base{
    public function index(){

        return $this->fetch();
    }
    public function welcome()
    {
        return $this -> view -> fetch('welcome');
    }

}