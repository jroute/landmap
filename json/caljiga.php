<?php 

include "../config/default.php"; 
include "../config/database.php"; 
include "../class/counter.cls.php"; 
include "../class/setup.cls.php"; 
include "../class/area.cls.php"; 
include "../class/gongsi_jiga.cls.php"; 


$counter = new Counter();

$counter->calculate(iconv('utf-8','euc-kr',$_POST['open_addr']));


$setup = new Setup();

$increases = $setup->getIncrease();

$calcost = $setup->getCalCost();

$open_cal_jiga = (int)$_POST['open_cal_jiga'];
$close_cal_jiga = (int)$_POST['close_cal_jiga'];


//print_r($_POST);
$sdate = $_POST['sdate'];
$edate = $_POST['edate'];

list($sy,$sm,$sd) = explode('-',$sdate);
list($ey,$em,$ed) = explode('-',$edate);

		
$lastDate[2] = date('t',mktime(0,0,0,$sm,$sd,$sy));

//정상지가 상승분
	$acc_total = $open_cal_jiga;

			trace("------------------- incress start ------------------------");

			trace('sdate : '.$sdate);
			trace('edate : '.$edate);			
			
	$increases_total = 0;
	$diffy = $ey - $sy;
	for($y = $sy ; $y <= $ey ; $y++ )
	{
		if( $y == $sy || $y == $ey ){
		
			$lastDate[2] = date('t',mktime(0,0,0,2,1,$y));	
			
			if( $diffy > 0 ){
				if( $y == $sy ){
					$_sm = (int)$sm;
					$_em = 12;
				}else if( $y == $ey ){
					$_sm = 1;
					$_em = (int)$em;				
				}
			}else{
				$_sm = (int)$sm;
				$_em = (int)$em;				
			}
			for($m = $_sm; $m<=$_em ; $m++){
				if( $y == $sy && $m == $sm ){
					$total = (int)($acc_total*$calcost[0]/100*($lastDate[$m]-(int)$sd)/$lastDate[$m]);
					trace("jiga : ".$m." MON1 : ".$total." = (".$acc_total."*".$calcost[0]."/100*".($lastDate[$m]-$sd)."/".$lastDate[$m].")");
					$acc_total += $total;			
					$increases_total += $total;
				}else if( $y == $ey && $m == $em ){
					$total = (int)($acc_total*$calcost[0]/100*(int)$ed/$lastDate[$m]);
					trace("jiga : ".$m." MON2 : ".$total." = (".$acc_total."*".$calcost[0]."/100*".$ed."/".$lastDate[$m].")");			
					$acc_total += $total;			
					$increases_total += $total;
				}else{
					$total = (int)($acc_total*$calcost[0]/100*$lastDate[$m]/$lastDate[$m]);			
					trace("jiga : ".$m." MON3 : ".$total." = (".$acc_total."*".$calcost[0]."/100*".$lastDate[$m]."/".$lastDate[$m].")");			
					$acc_total += $total;
					$increases_total += $total;			
		
				}
//			echo $_sm.' ' .$_em.' ' .$y. ' ' .$m . ' '. $acc_total.'<br />';	
			}//end of for $m
		}		
		else
		{
		
			$lastDate[2] = date('t',mktime(0,0,0,2,1,$y));		
			for($m = 1; $m<=12 ; $m++){
//			echo  $y. ' ' .$m . ' '. $acc_total.'<br />';				
					$total = (int)($acc_total*$calcost[0]/100*$lastDate[$m]/$lastDate[$m]);			
					trace("jiga : ".$m." MON3 : ".$total." = (".$acc_total."*".$calcost[0]."/100*".$lastDate[$m]."/".$lastDate[$m].")");			
					$acc_total += $total;
					$increases_total += $total;
			}//end of for		
		
		}
	
	}//end of for($y = $m ; $y <= $em ; $y++)
	

//개발비용
$devcost = (int)$_POST['devcost'];

//개발이익금
$dev_gain_fees = $close_cal_jiga - ($open_cal_jiga + $increases_total + $devcost);	

trace("dev_gain_fees :  ".$dev_gain_fees." = ".$close_cal_jiga." - (".$open_cal_jiga." + ".$increases_total." + ".$devcost.")");			

//개발부담금
$dev_impact_fees = intval($dev_gain_fees*0.25);
trace("dev_impact_fees :  ".$dev_impact_fees." = ".$dev_gain_fees." * 0.25");			

//개발부담금 10단위 절사
$dev_impact_fees = substr((String)$dev_impact_fees,0,-2).'00';

$data = array('dev_impact_fees'=>number_format($dev_impact_fees),
	'devcost'=>number_format($devcost),
	'increases'=>number_format($increases_total));

echo json_encode($data);
	
	
	
	
	
	
