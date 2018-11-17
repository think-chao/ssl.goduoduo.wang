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
use think\Loader;
use think\Request;
use PHPExcel_IOFactory;
use PHPExcel;
class leaders extends Base{
    public function leaderlist(){
        $data=db("leader")->select();
        $this->assign("data",$data);
        return $this->fetch();
    }
   
    public function del(){
        $username = $this->request->post('name');
	$card=$this->request->post('card');
	$sq="ALTER TABLE `leader` DROP `".$card."`";
	Db::execute($sq);
        $res=Db::table('leader')->where('username',$username)->delete();
	$res1=Db::table('week')->where('name',$username)->delete();
        if($res&$res1){
            return;
        }
    }

   public function aca_excel(){
        $dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
        $day=$dayOne."-----".$daySev;
        vendor("PHPExcel.PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.IWriter");
        vendor("PHPExcel.PHPExcel.Writer.Abstract");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        $sql =Db::table('week')->where('role',"院领导")->select();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('B1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('C1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('D1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('E1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('F1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('G1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('H1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('I1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('J1', '北京大学软件与微电子学院 院领导周工作安排表')
            ->setCellValue('K1', '北京大学软件与微电子学院 院领导周工作安排表')

            ->setCellValue('A2',$day)
            ->setCellValue('B2',$day)
            ->setCellValue('C2',$day)
            ->setCellValue('D2',$day)
            ->setCellValue('E2',$day)
            ->setCellValue('F2', $day)
            ->setCellValue('G2', $day)
            ->setCellValue('H2', $day)
            ->setCellValue('I2',$day)
            ->setCellValue('J2',$day)
            ->setCellValue('K2', $day)

            ->setCellValue('A3', '姓名')
            ->setCellValue('A4','姓名')
            ->setCellValue('B3', '星期一')
            ->setCellValue('C3', '星期一')
            ->setCellValue('B4','上午')
            ->setCellValue('C4','下午')
            ->setCellValue('D3', '星期二')
            ->setCellValue('E3', '星期二')
            ->setCellValue('D4','上午')
            ->setCellValue('E4','下午')
            ->setCellValue('F3', '星期三')
            ->setCellValue('G3', '星期三')
            ->setCellValue('F4','上午')
            ->setCellValue('G4','下午')
            ->setCellValue('H3', '星期四')
            ->setCellValue('I3', '星期四')
            ->setCellValue('H4','上午')
            ->setCellValue('I4','下午')
            ->setCellValue('J3', '星期五')
            ->setCellValue('K3', '星期五')
            ->setCellValue('J4','上午')
            ->setCellValue('K4','下午');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');


        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:C3');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:E3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:K3');



        $i=5;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($sql);  //计算有多少条数据
        for ($i = 5; $i <= $count+4; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sql[$i-5]['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $sql[$i-5]['am1']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sql[$i-5]['pm1']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $sql[$i-5]['am2']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $sql[$i-5]['pm2']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $sql[$i-5]['am3']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $sql[$i-5]['pm3']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $sql[$i-5]['am4']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $sql[$i-5]['pm4']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $sql[$i-5]['am5']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $sql[$i-5]['pm5']);

        }
        $objPHPExcel->getActiveSheet()->setTitle('productaccess');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
      //  $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");


        header('Content-Disposition: attachment;filename="院领导日程.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件

    }
    

    public function excel(){
        $dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
        $day=$dayOne."-----".$daySev;
        vendor("PHPExcel.PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.IWriter");
        vendor("PHPExcel.PHPExcel.Writer.Abstract");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        $sql =Db::table('week')->select();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('B1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('C1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('D1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('E1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('F1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('G1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('H1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('I1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('J1', '北京大学软件与微电子学院 领导周工作安排表')
            ->setCellValue('K1', '北京大学软件与微电子学院 领导周工作安排表')

            ->setCellValue('A2',$day)
            ->setCellValue('B2',$day)
            ->setCellValue('C2',$day)
            ->setCellValue('D2',$day)
            ->setCellValue('E2',$day)
            ->setCellValue('F2', $day)
            ->setCellValue('G2', $day)
            ->setCellValue('H2', $day)
            ->setCellValue('I2',$day)
            ->setCellValue('J2',$day)
            ->setCellValue('K2', $day)

            ->setCellValue('A3', '姓名')
            ->setCellValue('A4','姓名')
            ->setCellValue('B3', '星期一')
            ->setCellValue('C3', '星期一')
            ->setCellValue('B4','上午')
            ->setCellValue('C4','下午')
            ->setCellValue('D3', '星期二')
            ->setCellValue('E3', '星期二')
            ->setCellValue('D4','上午')
            ->setCellValue('E4','下午')
            ->setCellValue('F3', '星期三')
            ->setCellValue('G3', '星期三')
            ->setCellValue('F4','上午')
            ->setCellValue('G4','下午')
            ->setCellValue('H3', '星期四')
            ->setCellValue('I3', '星期四')
            ->setCellValue('H4','上午')
            ->setCellValue('I4','下午')
            ->setCellValue('J3', '星期五')
            ->setCellValue('K3', '星期五')
            ->setCellValue('J4','上午')
            ->setCellValue('K4','下午');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');


        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:C3');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:E3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:K3');



        $i=5;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($sql);  //计算有多少条数据
        for ($i = 5; $i <= $count+4; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sql[$i-5]['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $sql[$i-5]['am1']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sql[$i-5]['pm1']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $sql[$i-5]['am2']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $sql[$i-5]['pm2']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $sql[$i-5]['am3']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $sql[$i-5]['pm3']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $sql[$i-5]['am4']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $sql[$i-5]['pm4']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $sql[$i-5]['am5']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $sql[$i-5]['pm5']);

        }
        $objPHPExcel->getActiveSheet()->setTitle('productaccess');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
      //  $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");


        header('Content-Disposition: attachment;filename="领导日程.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件



    }

    public function agender(){
        //获取当天日期所在的一周的周一
        $dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
       // var_dump($dayOne);
        $dayTwo=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 2) * 24 * 3600));
        //var_dump($dayTwo);
        $dayThree=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 3) * 24 * 3600));
        //var_dump($dayThree);
        $dayFour=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 4) * 24 * 3600));
        //var_dump($dayFour);
        $dayFive=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 5) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
        //var_dump($dayFive);



        //本周周一所有人的数据
        $weekDayOne=Db::table('arrangement')->where('day',$dayOne)->order('wid')->select();
        //本周周二所有人的数据
        $weekDayTwo=Db::table('arrangement')->where('day',$dayTwo)->order('wid')->select();
        //本周周三所有人的数据
        $weekDayThree=Db::table('arrangement')->where('day',$dayThree)->order('wid')->select();
        //本周周四所有人的数据
        $weekDayFour=Db::table('arrangement')->where('day',$dayFour)->order('wid')->select();
        //本周周五所有人的数据
        $weekDayFive=Db::table('arrangement')->where('day',$dayFive)->order('wid')->select();

        $week=Db::table('week')->select();
	$major_week=Db::table('week')->where('role',"系负责人")->select();	

        $this->assign("week",$week);
        //var_dump($week);
        $data=Db::table('arrangement')->order('day')->select();
        $leader=Db::table('leader')->order('wid')->select();
        $this->assign("leader",$leader);
        $o=Db::table("arrangement")->where('name',$leader[0]['username']);

        $this->assign("dayOne",$dayOne);
        $this->assign("daySev",$daySev);

        $this->assign("data",$data);
        $this->assign("weekDayOne",$weekDayOne);
        $this->assign("weekDayTwo",$weekDayTwo);
        $this->assign("weekDayThree",$weekDayThree);
        $this->assign("weekDayFour",$weekDayFour);
        $this->assign("weekDayFive",$weekDayFive);

       return $this->fetch();

    }
	public function major_agender(){
	
	$dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
	$dayFive=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 5) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
	$major_week=Db::table('week')->where('role',"系负责人")->select();
	
	$this->assign("major_week",$major_week);
	$this->assign("dayOne",$dayOne);
        $this->assign("daySev",$daySev);

	return $this->fetch();
	
    }
	public function dep_agender(){

        $dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $dayFive=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 5) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
        $dep_week=Db::table('week')->where('role',"部门负责人")->select();

        $this->assign("dep_week",$dep_week);
        $this->assign("dayOne",$dayOne);
        $this->assign("daySev",$daySev);

        return $this->fetch();

    }
 public function aca_agender(){

        $dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $dayFive=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 5) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
        $aca_week=Db::table('week')->where('role',"院领导")->select();

        $this->assign("aca_week",$aca_week);
        $this->assign("dayOne",$dayOne);
        $this->assign("daySev",$daySev);

        return $this->fetch();

    }







    public function addleader(){
        return $this->fetch();
    }

    public function add(){
        $username = $this->request->post('username');
        $card = $this->request->post('card');
	$role = $this->request->post('role');
	if($role==1){
		$role="院领导";
	}
	if($role==2){
		$role="系负责人";
	}
	if($role==3){
		$role="部门负责人";
	}

        $data=['username'=>$username,'card'=>$card,'role'=>$role];
        $res=Db::table('leader')->insert($data);
        $res1=Db::table('week')->insert(['name'=>$username,'card'=>$card,'role'=>$role]);
//-------------------------
	$sq="ALTER   TABLE `leader` ADD `".$card."`  TINYINT(1) DEFAULT '0'";
	
	Db::execute($sq);
	if($res){    
	    $this->redirect('leaders/leaderlist');
	}
        else
            $this->redirect('addleader');
    }

public function major_excel(){
        $dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
        $day=$dayOne."-----".$daySev;
        vendor("PHPExcel.PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.IWriter");
        vendor("PHPExcel.PHPExcel.Writer.Abstract");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        $sql =Db::table('week')->where('role',"系负责人")->select();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('B1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('C1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('D1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('E1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('F1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('G1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('H1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('I1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('J1', '北京大学软件与微电子学院 系负责人周工作安排表')
            ->setCellValue('K1', '北京大学软件与微电子学院 系负责人周工作安排表')

            ->setCellValue('A2',$day)
            ->setCellValue('B2',$day)
            ->setCellValue('C2',$day)
            ->setCellValue('D2',$day)
            ->setCellValue('E2',$day)
            ->setCellValue('F2', $day)
            ->setCellValue('G2', $day)
            ->setCellValue('H2', $day)
            ->setCellValue('I2',$day)
            ->setCellValue('J2',$day)
            ->setCellValue('K2', $day)

            ->setCellValue('A3', '姓名')
            ->setCellValue('A4','姓名')
            ->setCellValue('B3', '星期一')
            ->setCellValue('C3', '星期一')
            ->setCellValue('B4','上午')
            ->setCellValue('C4','下午')
            ->setCellValue('D3', '星期二')
            ->setCellValue('E3', '星期二')
            ->setCellValue('D4','上午')
            ->setCellValue('E4','下午')
            ->setCellValue('F3', '星期三')
            ->setCellValue('G3', '星期三')
            ->setCellValue('F4','上午')
            ->setCellValue('G4','下午')
            ->setCellValue('H3', '星期四')
            ->setCellValue('I3', '星期四')
            ->setCellValue('H4','上午')
            ->setCellValue('I4','下午')
            ->setCellValue('J3', '星期五')
            ->setCellValue('K3', '星期五')
            ->setCellValue('J4','上午')
            ->setCellValue('K4','下午');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');


        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:C3');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:E3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:K3');



        $i=5;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($sql);  //计算有多少条数据
        for ($i = 5; $i <= $count+4; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sql[$i-5]['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $sql[$i-5]['am1']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sql[$i-5]['pm1']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $sql[$i-5]['am2']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $sql[$i-5]['pm2']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $sql[$i-5]['am3']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $sql[$i-5]['pm3']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $sql[$i-5]['am4']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $sql[$i-5]['pm4']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $sql[$i-5]['am5']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $sql[$i-5]['pm5']);

        }
        $objPHPExcel->getActiveSheet()->setTitle('productaccess');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
      //  $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");


        header('Content-Disposition: attachment;filename="系负责人日程.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
    }

   public function dep_excel(){
        $dayOne=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $daySev=date("Y-m-d",(time() - ((date('w') == 0 ? 7 : date('w')) - 7) * 24 * 3600));
        $day=$dayOne."-----".$daySev;
        vendor("PHPExcel.PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.IWriter");
        vendor("PHPExcel.PHPExcel.Writer.Abstract");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        $sql =Db::table('week')->where('role',"部门负责人")->select();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('B1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('C1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('D1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('E1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('F1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('G1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('H1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('I1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('J1', '北京大学软件与微电子学院 部门负责人周工作安排表')
            ->setCellValue('K1', '北京大学软件与微电子学院 部门负责人周工作安排表')

            ->setCellValue('A2',$day)
            ->setCellValue('B2',$day)
            ->setCellValue('C2',$day)
            ->setCellValue('D2',$day)
            ->setCellValue('E2',$day)
            ->setCellValue('F2', $day)
            ->setCellValue('G2', $day)
            ->setCellValue('H2', $day)
            ->setCellValue('I2',$day)
            ->setCellValue('J2',$day)
            ->setCellValue('K2', $day)

            ->setCellValue('A3', '姓名')
            ->setCellValue('A4','姓名')
            ->setCellValue('B3', '星期一')
            ->setCellValue('C3', '星期一')
            ->setCellValue('B4','上午')
            ->setCellValue('C4','下午')
            ->setCellValue('D3', '星期二')
            ->setCellValue('E3', '星期二')
            ->setCellValue('D4','上午')
            ->setCellValue('E4','下午')
            ->setCellValue('F3', '星期三')
            ->setCellValue('G3', '星期三')
            ->setCellValue('F4','上午')
            ->setCellValue('G4','下午')
            ->setCellValue('H3', '星期四')
            ->setCellValue('I3', '星期四')
            ->setCellValue('H4','上午')
            ->setCellValue('I4','下午')
            ->setCellValue('J3', '星期五')
            ->setCellValue('K3', '星期五')
            ->setCellValue('J4','上午')
            ->setCellValue('K4','下午');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');


        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:C3');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:E3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:K3');



        $i=5;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($sql);  //计算有多少条数据
        for ($i = 5; $i <= $count+4; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sql[$i-5]['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $sql[$i-5]['am1']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sql[$i-5]['pm1']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $sql[$i-5]['am2']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $sql[$i-5]['pm2']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $sql[$i-5]['am3']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $sql[$i-5]['pm3']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $sql[$i-5]['am4']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $sql[$i-5]['pm4']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $sql[$i-5]['am5']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $sql[$i-5]['pm5']);

        }
        $objPHPExcel->getActiveSheet()->setTitle('productaccess');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
      //  $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");


        header('Content-Disposition: attachment;filename="部门负责人日程.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件



    }
}
