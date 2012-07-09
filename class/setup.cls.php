<?php

class Setup {
	private $conn = null;
	private $db = '';
	function Setup(){
	
		$this->db = $_SERVER['DOCUMENT_ROOT'].'/mdb/setup.db';
		
	}
	
	function getIncrease(){

		$lines=file($this->db);

		return explode('|',$lines[0]);	
	}
	
	function getCalCost(){
		
		$lines=file($this->db);

		return explode('|',$lines[1]);		  	
	}
	
	function saveIncrease($data){
	
		$lines=file($this->db);
			
		$data = implode('|',$data);
		$fp = fopen($this->db,'w');
		fwrite($fp, $data."\n");
		fwrite($fp, $lines[1]);		
		fclose($fp);
	}
	
	function saveCalCost($data){
		$lines=file($this->db);
			
		$data = implode('|',$data);
		$fp = fopen($this->db,'w');
		fwrite($fp, $lines[0]);
		fwrite($fp, $data);		
		fclose($fp);
	}
	
	function close(){
		odbc_close($this->conn); 		
	}
}