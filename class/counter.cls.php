<?php

class Counter {
	private $conn = null;
	
	function Counter(){
		global $conn;
		$this->conn = $conn;
	}
			
	function access(){
	   $query      = "SELECT count(*) as total from access_counter where date='".date('Y-m-d')."'";
	   $res = mysql_query($query,$this->conn);
	   $data = mysql_fetch_assoc($res);
	   
	   if( $_COOKIE['access'] != '1' ){
		   if( $data['total'] ){
			   mysql_query("update access_counter set count=count+1 where date='".date('Y-m-d')."'",$this->conn);
			 }else{
				 mysql_query("insert into access_counter (date,count) values ('".date('Y-m-d')."',1)",$this->conn);		   
			 }
			 setcookie("access", "1", time()+86400, '/');
	   }	   
	}
	
	function calculate($addr){
		if( trim($addr) == '' ) return; 
		
	  $query      = "SELECT count(*) as total FROM calculate_counter WHERE date='".date('Y-m-d')."' AND address='".$addr."'";
	  $res = mysql_query($query, $this->conn);
	  $data = mysql_fetch_assoc($res);
	   
    if( $data['total'] ){
	    mysql_query("update calculate_counter set count=count+1 where date='".date('Y-m-d')."' AND address='".$addr."'",$this->conn) or die(mysql_error());
	  }else{
		  mysql_query("insert into calculate_counter (date,address,count) values ('".date('Y-m-d')."','".$addr."',1)",$this->conn) or die(mysql_error());		   
	  }

	}
	
	function today(){
		$res = mysql_query("select count from access_counter where date='".date('Y-m-d')."'",$this->conn) or die(mysql_error());
		$data = mysql_fetch_assoc($res);
		return $data['count'];
	}
	
	function total(){
		$res = mysql_query("select SUM(count) as total from access_counter",$this->conn) or die(mysql_error());
		$data = mysql_fetch_assoc($res);
		return $data['total'];
	}	
	
	
}