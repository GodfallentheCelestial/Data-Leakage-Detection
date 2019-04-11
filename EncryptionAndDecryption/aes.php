<?php
	
	/**
		 * Define the number of blocks that should be read from the source file for each chunk.
		 * For 'AES-128-CBC' each block consist of 16 bytes.
		 * So if we read 10,000 blocks we load 160kb into memory. You may adjust this value
		 * to read/write shorter or longer chunks.
	*/
	
	define("FILE_ENCRYPTION_BLOCKS",10000);
	/**
	 * Encrypt the passed file and saves the result in a new file with ".enc" as suffix.
	 * 
	 * @param string $source Path to file that should be encrypted
	 * @param string $key    The key used for the encryption
	 * @param string $dest   File name where the encryped file should be written to.
	 * @return string|false  Returns the file name that has been created or FALSE if an error occured
	*/
	
	function encryption($source,$key,$dest){
		
		if(file_exists($source)){
		$key = substr(sha1($key,true),0,16);
		$iv = openssl_random_pseudo_bytes(16);
		$error = false;
	
		if($fp = fopen($dest,'w')){
			//Put the IV to the beginning of the file
			fwrite($fp,$iv);
			if($fin = fopen($source,'rb')){
				while(!feof($fin)){
					$plain = fread($fin,16*FILE_ENCRYPTION_BLOCKS);
					$cipher = openssl_encrypt($plain,"aes-256-cbc",$key,OPENSSL_RAW_DATA,$iv);
					//Use the first 16  bytes of cipher as the next IV
					$iv = substr($cipher,0,16);
					fwrite($fp,$cipher);
				}
				fclose($fin);
			}
			else{
				throw new Exception("Source File of encryption cannot be opened in read binary mode");
			}
			fclose($fp);
		}
		else{
			throw new Exception("Destination file of Encryption can not be created");
		}
		
		}
	else{
		throw new Exception("Source File of Encryption does not exists");
	}
	return $dest;
	
	}
	
	
	function decryption($source,$key,$dest){
		$key = substr(sha1($key,true),0,16);
		
		if($fout = fopen($dest,'w')){
			if($fin = fopen($source,'rb')){
				// Get the IV from the beginning of the file
				$iv = fread($fin,16);
				while(!feof($fin)){
					//we have to read one block more for decrypting than for encrypting
					$cipher = fread($fin,16*(FILE_ENCRYPTION_BLOCKS+1)); 
					$plain = openssl_decrypt($cipher,"aes-256-cbc",$key,OPENSSL_RAW_DATA,$iv);
					//Use the first 16 bytes of the ciphertext as the next initialization vector
					$iv = substr($cipher,0,16);
					fwrite($fout,$plain);
				}
				fclose($fin);
			}
			else
				throw new Exception("Source file of Decryption cannot be opened in read binary mode");
		}
		else
			throw new Exception("Destination file of Decryption cannot be created");
		return $dest;
	}
?>