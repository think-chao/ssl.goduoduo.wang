<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Cookie;
use think\Session;
use app\index\model\admin;
use think\Db;
use think\Request;

class index extends Base
{
    public function index()
    {
        return $this->fetch("login");
    }

public function weixiao_api($content){
        $url = 'https://icampus.ss.pku.edu.cn/iaaa/index.php/Home/OpenApi/decode';
        $data = ["appid"=>'sspkum3432cxjb6fxu','appsecret'=>'6a42b1c544a94854e2221a0ecb9fe25c','content'=>$content];
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    );
    /* 根据请求类型设置特定参数 */
    $opts[CURLOPT_URL] = $url ;
        $opts[CURLOPT_POST] = 1;
        $opts[CURLOPT_POSTFIELDS] = $data;
        if(is_string($data)){ //发送JSON数据
            $opts[CURLOPT_HTTPHEADER] = array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data),
            );
        }
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    //发生错误，抛出异常
    if($error) throw new \Exception('请求发生错误：' . $error);
//    $res =$this->res_to_db($data);
    return $data;
    }

    public function check()
    {
        //获取一下表单提交的数据,并保存在变量中
        $username = $this->request->post("username");
        $password = $this->request->post("password");
       /* $captcha = $this->request->post("captcha");

        if(!captcha_check($captcha)){
            // 验证码错误
            // return $this->ret["captchaError"];
             $this->error("验证码错误");
        }*/

        $select = Db::query("select *from admin where username='$username' and password=md5('$password')"); // 执行查询
        // 获取信息对象
        if($select){
            Session::set("username",$username);
            Cookie::set("username",$username);
            $this->redirect('home/index');
        }else{
            $this->error('用户名或密码错误');
        }


    }
    public function logout(){
        // 清除session
        Session::delete('username');
        // 跳转到login页面
        $this->redirect('index');
    }

}
