<?php
	//Seperate digit and convert digit to binary
	function encodeUID($uid){
	$no = $uid;
	$binary='';
	do{
		$rem = $no%10;
		$binary = decimalToBinary($rem).$binary;
		$no = $no/10;
	}while($no>0 && strlen($binary)<16);
	return $binary;
	}
	
	//Convert digit into binary
	function decimalToBinary($no){
	$binary='';
	if($no==0){
		return '0000';
	}
	do{
		$rem = ($no%2);
		$binary =$rem.$binary;
		$no = $no/2;
	}while($no>0 && strlen($binary)<4);
	return $binary;
	}
	
	// add data to the end of file
	function embedPdf($file1,$appendedData){
		//$file = fopen($file1,"a+") or die("Unable to open file");
		$srcroute = realpath($file1);
		$file = fopen($srcroute,"a+") or die("unable to open file");
		fwrite($file,$appendedData);
		fclose($file);
	}
	
	// Convert uid (binary) into \n\t format
	function makeUIDPdf($uid){
		$result="";
		for($i=0;$i<strlen($uid);$i++){
			if($uid[$i]=='1')
				$result .= '\t';
			else
				$result .= '\n';
		}
	return $result;
	}
	
	//Extract data and convert into 0&1
	function extractPdfData($file){
		$path = realpath($file);
		echo $path;
		$f = fopen($path,"r") or die("Unable to open file");
		$lastline="";
		while(!feof($f)){
			$lastline = fgets($f);
		}
		$data="";
		for($i=0;$i<strlen($lastline);$i=$i+2){
			if($lastline[$i]=='\\' and $lastline[$i+1]=='n')
				$data .= '0';
			else
				$data .= '1';
		}
		return $data;
	}
	
	//Return array of UID 
	function findUID($data){
		$power = array(8,4,2,1);
		$uidList = "";
		$list = array();
		
		for($i=0;$i<strlen($data);$i=$i+16){
			$uid="";
			for($j=$i,$word=0;$word<16;$j=$j+4,$word+=4){
				$digit=0;
				for($k=$j,$pos=0; $pos<4 ;$k++,$pos++){
					if($data[$k]==1){
						$digit = $digit+$power[$pos];
					}
				}
				
				$uid.=$digit;
			}
			$list[] = $uid; 
		}
		return $list;
	}
	
?>