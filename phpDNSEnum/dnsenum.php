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
		}
		
		$f = fopen($wordlist, "rb");
		$sword = stream_get_contents($f);
		fclose($f);
		
		$words = explode("\n", $sword);
		
		echo "DNS Enumeration is starting ...\n";
		
		foreach($words as $sub){
			$hostname = $sub . "." . $domain;
			$check = gethostbyname($hostname);
			
			if($check != $hostname){
				echo $hostname . "\n";   
			}
		}
		
		echo "\n\nScan completed.";
	}
}else{
	die("Arg Error: Domain name is required. Example: 'php dnsenum.php google.com'");
}

