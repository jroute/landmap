<?php

class Info {
	private $conn = null;
	
	function Info(){
		global $config,$conn;

		$this->conn = $conn;

		
	}
	
	function getProperty($p){
		//$p=02:용도지역, 06=토지이용상황
		
		$query      = "SELECT LDP_CD,LDP_CD_NM FROM C_PROPERTY WHERE LDP_ITEM_CD='$p' order by LDP_CD_NM ASC";

    $result     = mysql_query($query,$this->conn) or die('::error:::'.mysql_error($conn));

    $umd = array();
		while( $row = mysql_fetch_assoc($result) ) {
		if( trim($row['LDP_CD']) == '' ) continue;
			$umd[$row['LDP_CD']] = $row['LDP_CD_NM'];
		}		
		mysql_free_result($result);		

		return $umd;	
	}
	
	function close(){
		mysql_close($this->conn); 		
	}
}