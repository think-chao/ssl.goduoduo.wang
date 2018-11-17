<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 2018/5/2
 * Time: 9:12
 */
namespace app\order\controller;
use app\order\controller\Base;
use app\order\api\WXBizDataCrypt;
use think\Cookie;
use think\Session;

use think\Db;
use think\Request;
class Login extends Base{

    /**
     * 发送HTTP请求方法，目前只支持CURL发送请求
     * @param  string $url    请求URL
     * @param  array  $param  GET参数数组
     * @param  array  $data   POST的数据，GET请求时该参数无效
     * @param  string $method 请求方法GET/POST
     * @return array          响应数据
     */
    public static function http($url, $param, $data = '', $method = 'GET'){
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );

        /* 根据请求类型设置特定参数 */
        $opts[CURLOPT_URL] = $url . '?' . http_build_query($param);

        if(strtoupper($method) == 'POST'){
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $data;
            
            if(is_string($data)){ //发送JSON数据
                $opts[CURLOPT_HTTPHEADER] = array(
                    'Content-Type: application/json; charset=utf-8',  
                    'Content-Length: ' . strlen($data),
                );
            }
        }

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        //发生错误，抛出异常
        if($error) throw new \Exception('请求发生错误：' . $error);

        return  $data;
    }
   

    function checkLogin(){
    //$appid="sspkum3432cxjb6fxu";
    //$secret="6a42b1c544a94854e2221a0ecb9fe25c";
	$params=[
		'appid'=> "sspkum3432cxjb6fxu",
		'appsecret'=>"6a42b1c544a94854e2221a0ecb9fe25c"
	];
     $url="https://icampus.ss.pku.edu.cn/iaaa/index.php/Home/OpenApi/getGrantUrl";
    $res=$this->http($url,$params);
        $params1=[
            'appid'=> "sspkum3432cxjb6fxu",
            'appsecret'=>"6a42b1c544a94854e2221a0ecb9fe25c",
            'content'=>$res
        ];
        $url1="https://icampus.ss.pku.edu.cn/iaaa/index.php/Home/OpenApi/decode";
        $jsondata=$this->http($url1,$params1);
        $data=json_decode($jsondata);
        return json(array("role"=>2,'data'=>  $data));

   
    }



function ret_message($message = "") {
    if ($message == "") return ['result'=>0, 'message'=>''];
    $ret = lang($message);

    if (count($ret) != 2) {
        return ['result'=>-1,'message'=>'未知错误'];
    }
    return array(
        'result'  => $ret[0],
        'message' => $ret[1]
    );
}

function randomFromDev($len) {
    $fp = @fopen('/dev/urandom','rb');
    $result = '';
    if ($fp !== FALSE) {
        $result .= @fread($fp, $len);
        @fclose($fp);
    }
    else
    {
        trigger_error('Can not open /dev/urandom.');
    }
    // convert from binary to string
    $result = base64_encode($result);
    // remove none url chars
    $result = strtr($result, '+/', '-_');

    return substr($result, 0, $len);
}


}
