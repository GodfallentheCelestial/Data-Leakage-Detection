
<?php

		if(isset($_FILES['file1'])){
		$errors = array();
		
		//Storing File on Temporary Folder "filestToUpload"
		$file1_name = $_FILES['file1']['name'];
		$file1_size = $_FILES['file1']['size'];
		$file1_tmp = $_FILES['file1']['tmp_name'];
		
		$file2_name = $_FILES['file2']['name'];
		$file2_size = $_FILES['file2']['size'];
		$file2_tmp = $_FILES['file2']['tmp_name'];
		
		
		 move_uploaded_file($file1_tmp,"Similarity/".$file1_name);
		 move_uploaded_file($file2_tmp,"Similarity/".$file2_name);
		
		
		//Reading File
		$fp1 = fopen("Similarity/".$file1_name,"r");
		$ff1 = fread($fp1,filesize("Similarity/".$file1_name));
		
		
		$fp2 = fopen("Similarity/".$file2_name,"r");
		$ff2 = fread($fp2,filesize("Similarity/".$file2_name));
		
		
		//Words in Array
	
		$doc1 = array_count_values(str_word_count($ff1, 1)) ;
		$doc2 = array_count_values(str_word_count($ff2,1));
		
		
		//Calculating ||d1|| and ||d2||
		$d1=0;
		foreach($doc1 as $i => $i_value){
			$d1 = $d1+($i_value*$i_value);
		}
		$d1 = sqrt($d1);
		
		
		$d2=0;
		foreach($doc2 as $x=> $x_value){
			$d2 = $d2 + ($x_value*$x_value);
		} 
		$d2 = sqrt($d2);
		
		
		//Calculating d1 and d2
		$d1d2=0;
		foreach($doc1 as $y=>$y_value){
			if(array_key_exists($y,$doc2)){
				$d1d2 += ($y_value*$doc2[$y]);
			}
		}
		
		//Calculating result 
		
		$res = ($d1d2/($d1*$d2))*100;
		include('CosineSimilarityResult.html');
		
		
		//File CLose
		fclose($fp1);
		fclose($fp2);
		}
?>
