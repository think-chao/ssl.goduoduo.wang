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

    private function createNonceStr($length = 16) {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for ($i = 0; $i < $length; $i++) {
     		 $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
   }
   
    function checkLogin(){
       // $appid="wx94e72edfe0955226";
	$appid = "wxcc62d0c8b79004ff";
      //  $secret="c7cf8bf2222af2ed265ca56c1106a704";
	$secret = "80118935b9f48464331aa23f407ca590";
        $code=$this->request->param('code');
	//$nonceStr = $this->createNonceStr();
	//var_dump($nonceStr);
	//$ts = time();
        
	//$encryptedData = $this->request->param('encryptedData');
        //$rawData = Request::instance()->param("rawData");
        //$signature =  Request::instance()->param("signature"); 
        //$iv =  Request::instance()->param("iv");
        $grant_type="authorization_code";

        trace($code);
	$params = [
           'appid' => $appid,
           'secret' => $secret,
           'js_code' => $code,
           'grant_type' => $grant_type
        ];

   $url="https://api.weixin.qq.com/sns/jscode2session";
   

   $res = $this->makeRequest($url, $params);
    
	var_dump(__LINE__);
    if ($res['code'] !== 200 || !isset($res['result']) || !isset($res['result'])) {
        return json($this->ret_message('requestTokenFailed'));
    }
        $reqData = json_decode($res['result'], true);
	trace("######################################## done #########################################");	
  
        $aaa=json_encode($reqData);
	trace("&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& res &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&");
	trace($res);
	trace("reqData  : $aaa","info" );
    if (!isset($reqData['session_key'])) {
       return json($this->ret_message('requestTokenFailed'));
    }
  	trace("session..............","info" );
    $sessionKey = $reqData['session_key'];
    $signature2 = sha1($rawData . $sessionKey);

	var_dump(__LINE__);
    if ($signature2 !== $signature) return $this->ret_message("signNotMatch");

    /**
     *
     * 6.使用第4步返回的session_key解密encryptData, 将解得的信息与rawData中信息进行比较, 需要完全匹配,
     * 解得的信息中也包括openid, 也需要与第4步返回的openid匹配. 解密失败或不匹配应该返回客户相应错误.
     * （使用官方提供的方法即可）
     */
    $pc = new WXBizDataCrypt($appid, $sessionKey);
    $errCode = $pc->decryptData($encryptedData, $iv, $data);

    if ($errCode !== 0) {
        return json($this->ret_message("encryptDataNotMatch"));
    }

    /**
     * 7.生成第三方3rd_session，用于第三方服务器和小程序之间做登录态校验。为了保证安全性，3rd_session应该满足：
     * a.长度足够长。建议有2^128种组合，即长度为16B
     * b.避免使用srand（当前时间）然后rand()的方法，而是采用操作系统提供的真正随机数机制，比如Linux下面读取/dev/urandom设备
     * c.设置一定有效时间，对于过期的3rd_session视为不合法
     *
     * 以 $session3rd 为key，sessionKey+openId为value，写入memcached
     */
    $data = json_decode($data, true);
    $session3rd ="";//$this-> randomFromDev(16);

    $data['session3rd'] = $session3rd;
    cache($session3rd, $data['openId'] . $sessionKey);


    $s=json_encode( $data);

//	return json(array("data"=>true));
    //$rows1 = db("office")->where('wid',$s -> openId)->find();

    $q=$data;		
    $rows1=db("leader")->where("wid",$q["openId"])->find();
  
    $q=$data;
    $row=json_decode($rawData ,true);
    trace("sssssssssssss  ","info"); 
    if(empty($rows1)){
	
//	  $l["wid"]=$q["openId"];
	$wwwid=$q["openId"];
	$card=db("leader")->where("biao",1)->field("card")->find(); 
	
       
    //   db("leader")->where("biao",1)->update(["biao"=>0,"wid"=>$l["wid"]]); 
	db("leader")->where("biao",1)->update(["biao"=>0,"wid"=>$wwwid]);
	trace("wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwid","wid");	  
         
	     
 $data = db("week")->where("card",$card["card"]  )->update(["wid"=>$q["openId"]]); 
		 $data=db("week")->where("wid",  $q["openId"])->select( );
	
	$dayOne=json_encode(date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));
        $daySev=json_encode(date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600)));
	return json(array("data"=> $data,'wid'=>$q["openId"],'dayone'=>$dayOne,'daysev'=>$daySev,'mess'=>000));
    }
    else {

	$card=db("leader")->where("wid",$q["openId"])->field("card")->find(); 
	$data = db("week")->where("wid",$q["openId"]  )->select( );
       $a=json_encode($data);  
        trace("qqqqqqqqq$a");
	$dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
	var_dump($dayOne);
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
	  return json(array('data'=>  $data,'wid'=>$q["openId"],'dayone'=>$dayOne,'daysev'=>$daySev,'mess'=>111));
    }

   }



    function makeRequest($url, $params = array(), $expire = 0, $extend = array(), $hostIp = '')
{
    if (empty($url)) {
        return array('code' => '100');
    }

    $_curl = curl_init();
    $_header = array(
        'Accept-Language: zh-CN',
        'Connection: Keep-Alive',
        'Cache-Control: no-cache'
    );
    // 方便直接访问要设置host的地址
    if (!empty($hostIp)) {
        $urlInfo = parse_url($url);
        if (empty($urlInfo['host'])) {
            $urlInfo['host'] = substr(DOMAIN, 7, -1);
            $url = "http://{$hostIp}{$url}";
        } else {
            $url = str_replace($urlInfo['host'], $hostIp, $url);
        }
        $_header[] = "Host: {$urlInfo['host']}";
    }

    // 只要第二个参数传了值之后，就是POST的
    if (!empty($params)) {
        curl_setopt($_curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($_curl, CURLOPT_POST, true);
    }

    if (substr($url, 0, 8) == 'https://') {
        curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($_curl, CURLOPT_URL, $url);
    curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($_curl, CURLOPT_USERAGENT, 'API PHP CURL');
    curl_setopt($_curl, CURLOPT_HTTPHEADER, $_header);

    if ($expire > 0) {
        curl_setopt($_curl, CURLOPT_TIMEOUT, $expire); // 处理超时时间
        curl_setopt($_curl, CURLOPT_CONNECTTIMEOUT, $expire); // 建立连接超时时间
    }

    // 额外的配置
    if (!empty($extend)) {
        curl_setopt_array($_curl, $extend);
    }

    $result['result'] = curl_exec($_curl);
    $result['code'] = curl_getinfo($_curl, CURLINFO_HTTP_CODE);
    $result['info'] = curl_getinfo($_curl);
    if ($result['result'] === false) {
        $result['result'] = curl_error($_curl);
        $result['code'] = -curl_errno($_curl);
    }

    curl_close($_curl);
    return $result;
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
