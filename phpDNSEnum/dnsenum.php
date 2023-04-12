<?php
//header("Content-Type: text/plain");

$nargv = count($argv);

if(count($argv) > 1){
	$wordlist = "../dns.txt";
	
	if(
		in_array("help", $argv) 	||
		in_array("h", $argv)		||
		in_array("-h", $argv)		||
		in_array("--h", $argv)		||
		in_array("--help", $argv)	||
		in_array("-help", $argv)
	){
		echo "A Simple DNS Enum Script in PHP\n";
		echo "Usage: \n";
		echo "\tphp dnsenum.php <domain name>\n";
		echo "\tphp dnsenum.php <wordlist path> <domain name>\n";
		echo "\n";
		die();
	}else{
		$domain = "";
		
		if($nargv == 2){
			$domain = $argv[1];
		}elseif($nargv == 3){
			$wordlist 	= $argv[1];
			$domain 	= $argv[2];
		}else{
			die("Arg Error");
		}
		
		if(!file_exists($wordlist)){
			die("Wordlist is not exists: " . $wordlist);
		}else{
			echo "Wordlist: " . $wordlist . "\n";
		}
		
		$size = filesize($wordlist);
		$threading = false;
		
		if($size > 5000000){
			$threading = false;
		}
		
		$f = fopen($wordlist, "rb");
		
		echo "DNS Enumeration is starting ...\n";
		$start = time();
		
		if($f){
			while(!feof($f)){
				$sub = fgets($f);
				$hostname = trim($sub, "\n") . "." . $domain;
				$check = gethostbyname($hostname);
				
				if($check != $hostname){
					echo $hostname . "\n";   
				}
			}
			
			fclose($f);
		}else{
			echo "Fail opening source. \n";
		}
		
		$end = time();
		
		echo "...\nScan completed in " . ($end - $start) . " second(s).";
	}
}else{
	die("Arg Error: Domain name is required. Example: 'php dnsenum.php google.com'");
}

