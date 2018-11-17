<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 2018/5/1
 * Time: 22:47
 */
namespace app\order\controller;
use think\Controller;
use think\Session;
use think\Db;
use think\Request;
use app\order\controller\Login;
class Index  extends Base{
    public function index(){

	if(null!=Request::instance()->param("rawdata")){
	        $weixiao = Request::instance()->param("rawdata");

		$res = $this->weixiao_api($weixiao);
		$arr=json_decode($res,true);
		$ss_number=$arr["data"]["card_number"];
		$ss_name=$arr["data"]["name"];
		//var_dump($ss_number);
		//var_dump($ss_name);
		$is_find=Db::table('leader')->where('card',$ss_number)->find();
		if($is_find){
			$w =db("leader")->where("card",$ss_number) ->field("wid")->find();
		 	if($w["wid"]!=null){
				return $this->fetch();
			}else
			{
		 		$row1=db("leader")->where("biao",1)->find();
				while($row1!= null){
					$row1=db("leader")->where("biao",1)->find();
				}	
		  		db("leader")->where("card",$ss_number) -> update(["biao"=>1]);	
			 	return $this->fetch();
			}
		}else{
			$this->redirect("https://ssl.goduoduo.wang");
			
			//echo "您暂时还没有权限进入，请联系办公室人员添加权限";
		}
		//echo "hello";	
	}else{
	return $this->fetch();
	}
    }
    
   function list_leader(){
				$card_number_own=Request::instance()->param("id_own");
	trace("00000000000000000000000000000000000");
	//$leader=Db::table('leader')->select();
	//return json(array("data"=>$leader));
	$sq="SELECT `".$card_number_own."` FROM `leader`";
        $gu=Db::query($sq);
        $ss=json_encode($gu);
        trace("0000000000000000000000000$ss");
        $guanzhu=array();
        foreach($gu as $vo){
                array_push($guanzhu,$vo[$card_number_own]);
        }
	 $statusNew_name=Db::table('leader')->column("username");

        $card=Db::table('leader')->column("card");
        return json(array("newStatus"=>$statusNew_name,"guanzhu"=>$guanzhu,"card"=>$card));
  
}

    
    function quguan(){
	// $card_number_own=Request::instance()->param("id_own");
	//$card_number=Request::instance()->param("id");
	//$res=Db::table('leader')->where('card',$card_number)->update([$card_number_own=>0]);
	//$statusNew=Db::table('leader')->select();
	//return json(array("newStatus"=>$statusNew));

	 $card_number_own=Request::instance()->param("id_own");
         $card_number=Request::instance()->param("id");
       // $res=Db::table('leader')->where('card',$card_number)->update([$card_number_own=>1]);
        $sq="UPDATE `leader` SET `".$card_number_own."` = '0' WHERE `leader`.`card`=".$card_number;
        Db::execute($sq);
        $statusNew_name=Db::table('leader')->column("username");
        $sq="SELECT `".$card_number_own."` FROM `leader`";
        $gu=Db::query($sq);
        $ss=json_encode($gu);
        trace("0000000000000000000000000$ss");
        $guanzhu=array();
        foreach($gu as $vo){
                array_push($guanzhu,$vo[$card_number_own]);
        }
        $card=Db::table('leader')->column("card");
        return json(array("newStatus"=>$statusNew_name,"guanzhu"=>$guanzhu,"card"=>$card));

    }

   function guanzhu(){
	$card_number_own=Request::instance()->param("id_own");
	 $card_number=Request::instance()->param("id");
       // $res=Db::table('leader')->where('card',$card_number)->update([$card_number_own=>1]);
	$sq="UPDATE `leader` SET `".$card_number_own."` = '1' WHERE `leader`.`card`=".$card_number;
	Db::execute($sq);
	$statusNew_name=Db::table('leader')->column("username");
	$sq="SELECT `".$card_number_own."` FROM `leader`";
	$gu=Db::query($sq);
	$ss=json_encode($gu);
	trace("0000000000000000000000000$ss");
	$guanzhu=array();
	foreach($gu as $vo){
		array_push($guanzhu,$vo[$card_number_own]);
	}
	$card=Db::table('leader')->column("card");
        return json(array("newStatus"=>$statusNew_name,"guanzhu"=>$guanzhu,"card"=>$card));
    }



   function getguanzhu(){
	
        $card=Request::instance()->param("id");
        $ziji=db("week")->where("card",$card)->find();
	
	
	$q=db("leader")->where("card",$card)->column("card");

$t0=implode('',$q);	
	
	$sq="SELECT  `card` FROM `leader` WHERE `".$card."` =1";
	



	//$con=mysqli_connect("114.115.185.173","psydev","17psy@sspku","potato");
	//$ret=mysqli_query($con,$sq  );
	
	//$gz=mysqli_fetch_array($ret);
	trace("sq...............$sq"); 

	$gz=Db::query( $sq);

	trace("card.................$card");
	
	//$gz=db("leader")->where("1701210366",1)->column("card" );



	
$res=array();	
	foreach($gz as $q){
	
	array_push($res,$q["card"]);
	}
	$data=db("week")->where("card","in",$res)->select( );
	 if($data==null){
	
	return json(array("status"=>0));
	}else{
	    
	       return json(array("status"=>1,"ziji"=>$ziji, "data"=> $data));
		
	}

} 


    function insert(){
	
     $a['a']= Request::instance()->param("index");
     $a['p']= Request::instance()->param("index1");
     $a["name"]= Request::instance()->param("name");
      $a['day'] = Request::instance()->param("date");

    $wid=Request::instance()->param("wid");
    $co["wid"]=$wid;
    $co["day"]=$a["day"];
    $result=db("arrangement")->where( $co)->find();
    $s=json_encode($wid);
    trace("wid  $wid","info" );
   $res=false;

	 switch($a["a"]){
 	case 0:
        	$a["a"]="本部";
		break;

 	case 1:
 		$a["a"]="大兴";
		break;
 	case 2:
		$a["a"]="出差";
		break;
	case 3:
                $a["a"]="无锡";
                break;

	case 4:
                $a["a"]="深圳";
                break;
        case 5:
                $a["a"]="请假";
                break;
        case 6:
                $a["a"]="工博";
                break;
        default:
                $a["a"]="待定";
	}


 	switch($a["p"]){
 	case 0:
        	$a["p"]="本部";
        	break;
 	case 1:
        	$a["p"]="大兴";
        	break;
 	case 2:
        	$a["p"]="出差";
        	break;
	case 3:
        	$a["p"]="无锡";
		break;
	case 4:
		$a["p"]="深圳";
		break;
	case 5:
		$a["p"]="请假";
		break;
	case 6:
		$a["p"]="工博";
		break;
	default:
		$a["p"]="待定";

	}

	$weekarray=array(0,1,2,3,4,5,6);
	$day=json_encode($weekarray[date("w",strtotime($a["day"]))]);
  	trace("week  $day","info");
    	db("week")->where("wid",$wid)->update(["am".$day =>$a["a"], "pm".$day =>$a["p"]]);
    	if(empty($result)){
	  
           	 $a["wid"]=$wid;

         	if( db("arrangement")->insert($a))
            	$res=true;

    	}else{
        	db("arrangement")->where("wid", $wid)->  update ([ "a" =>$a["a"],"p"=>$a ["p"]]);
           	$res=true;  }
    	return json_encode(array("res"=>$res));
   
	}

	
	function update(){
	$wid=Request::instance()->param("wid");
	$row=db("week")->where("wid",$wid )->select( );
        trace("传过来的wid是  $wid");
   	return json(array("data"=>$row,"wid"=>$wid));

	}

public function weixiao_api($content){
        $url = 'https://icampus.ss.pku.edu.cn/iaaa/index.php/Home/OpenApi/decode';
        $data = ["appid"=>'sspkum3432cxjb6fxu','appsecret'=>'6a42b1c544a94854e2221a0ecb9fe25c','content'=>$content];
//               $data = json_encode($data_tmp);
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





}
