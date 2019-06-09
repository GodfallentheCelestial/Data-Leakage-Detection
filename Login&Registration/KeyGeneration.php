<?php

	function generateKey($uid){
		$key = "&&**(%^)(%^)".$uid;
		return hash("sha256",$key);
		
	}

?>