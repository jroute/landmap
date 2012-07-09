<?php

class GongsiJiga {
	private $conn = null;
	
	function GongsiJiga(){
		global $config,$conn;

		$this->conn = $conn;

		
	}
	
	function getYearJiga($umd,$ri,$g,$s,$e){
	
			$umd = $umd ? trim($umd):'000';
			$ri = $ri ? trim($ri):'00';
			$g = $g ? trim($g):'0';
			$s = $s ? sprintf('%04d',trim($s)):'0000';
			$e = $e ? sprintf('%04d',trim($e)):'0000';
				
			$cd = $umd.$ri.$g.$s.$e;						
    $query      = "SELECT YEAR,JIGA FROM JIGA WHERE LAND_CD='44270".$cd."'";//'44270".$cd."%'";

    trace("년도별 공시지가 쿼리 : ".$query);
    $result     = mysql_query($query,$this->conn) or die('::error::');
    trace("년도별 공시지가 rows : ".mysql_num_rows($result));  


    $jiga = array();
		while( $row = mysql_fetch_array($result) ) {
			$jiga[] = $row;
		}		
		mysql_free_result($result);		

		return $jiga;	
	}
	
	function getAreaJiga($umd,$ri,$g,$s,$e){
			global $testmode;
			
//	function getAreaJiga($cd){
			$umd = $umd ? trim($umd):'000';
			$ri = $ri ? trim($ri):'00';
			$g = $g ? trim($g):'0';
			$s = $s ? sprintf('%04d',trim($s)):'0000';
			$e = $e ? sprintf('%04d',trim($e)):'0000';
			
			$cd = $umd.$ri.$g.$s.$e;		
	  $query      = "SELECT LAND_CD,LAND_AREA,JIGA FROM LAND WHERE LAND_CD='44270".$cd."'";
    trace("지역별 공시지가 쿼리 : ".$query);
//echo $query;
    $result     = mysql_query($query,$this->conn);
    trace("지역별 공시지가 rows : ".mysql_num_rows($result));  
    
    $jiga = mysql_fetch_array($result);
    
	  mysql_free_result($result);
	  
//	  	$jiga['JIGA']= '42000';
//	  	$jiga['LAND_AREA']= '420';	  	
	  	
	    return $jiga;
	}
	
	
	
	
	function getStateJiga($umd,$ri,$use,$state){
			global $testmode;
			
			$umd = $umd ? trim($umd):'000';
			$ri = $ri ? trim($ri):'00';
			$use = $use ? trim($use):'00';
			$state = $state ? sprintf('%03d',trim($state)):'000';

			
	  $query      = "SELECT LAND_CD,LAND_AREA,JIGA FROM LAND WHERE SIDOSGG_CD='44270' AND UMD_CD='$umd' AND RI_CD='$ri' AND USE_REGN1='$use' AND LAND_USE='$state' group by JIGA";
    trace("State Jiga query : ".$query);  


    $result     = mysql_query($query,$this->conn);
    trace("State Jiga rows : ".mysql_num_rows($result));      

    $jiga = array();
    while($row = mysql_fetch_assoc($result)){
	    $jiga[] = $row;
    }
    
	  mysql_free_result($result);

	  	
	    return $jiga;
	}
	
	
	function close(){
		mysql_close($this->conn); 		
	}
}