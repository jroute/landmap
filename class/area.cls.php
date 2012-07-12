<?php

class Area {
	private $conn = null;
	
	function Area(){
		global $config,$conn;

		$this->conn = $conn;

		
	}
	
	function getUMD(){
	  $query      = "SELECT UMD_CD,UMD_NM FROM C_AREACODE WHERE SIDOSGG_CD='44270' order by UMD_NM DESC";
	  trace("?/?/Óú query : ".$query);
    $result     = mysql_query($query,$this->conn) or die(mysql_error());
    trace("?/?/Óú rows : ".mysql_num_rows($result));
    $umd = array();
		while( $row = mysql_fetch_assoc($result) ) {
		if( trim($row['UMD_NM']) == '' ) continue;
			$umd[$row['UMD_CD']] = $row['UMD_NM'];
		}		
		mysql_free_result($result);		
	  $umd = array_unique($umd);

		asort($umd);	
		return $umd;	
	}
	
	function getRI($q){
	  $query      = "SELECT RI_CD,RI_NM FROM C_AREACODE WHERE SIDOSGG_CD='44270' AND RI_CD!='00' AND UMD_CD='".$q."' order by RI_NM DESC";
	  trace("Ý¤ query : ".$query);
    $result     = mysql_query($query,$this->conn);
    trace("Ý¤ rows : ".mysql_num_rows($result));
    $ri = array();
    while( $row = mysql_fetch_array($result) ) {
	    if( trim($row['RI_NM']) == '' ) continue;
	    $ri[$row['RI_CD']] = $row['RI_NM'];
	  }

	  mysql_free_result($result);
	    return $ri;
	}
	
	function getAddr($umd,$ri){
		$ri = trim($ri)?trim($ri):'00';		
		$query      = "SELECT SGG_NM,UMD_CD,UMD_NM,RI_CD,RI_NM FROM C_AREACODE WHERE SIDOSGG_CD='44270' AND UMD_CD='".$umd."' AND RI_CD='".$ri."'";
    trace("×ùÊ« query : ".$query);
   $result     = mysql_query($query,$this->conn);
    trace("×ùÊ« rows : ".mysql_num_rows($result));
    $row = mysql_fetch_assoc($result);
    
	  mysql_free_result($result);
	  
	  return $row;		
	}
	
	function close(){
		mysql_close($this->conn); 		
	}
}