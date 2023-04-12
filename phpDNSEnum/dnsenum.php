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
		
		$size = filesize($wordlist);
		// $threading = false;
		
		// if($size > 5000000){
			// $threading = false;
		// }
		
		$default = dns_get_record($domain);		
		echo "\nDNS Recource Records:\n_______________________\n";
		
		// print_r($default);
		
		foreach($default as $d){
			
			switch($d["type"]){
				case "A":
					echo $d["type"] . "\t- " . $d["host"] . " - " . (isset($d["ip"]) ? $d["ip"] : "NONE" ) . " [". $d["type"] . "|" . $d["class"] ."] TTL=" . $d["ttl"] . "\n";
				break;
				
				case "AAAA":
					echo  $d["type"] . "\t- " . $d["host"] . " - " . (isset($d["ipv6"]) ? $d["ipv6"] : "NONE" ) . " [". $d["type"] . "|" . $d["class"] ."] TTL=" . $d["ttl"] . "\n";
				break;
				
				case "TXT":
					echo  $d["type"] . "\t- " . $d["host"] . " - " . (isset($d["txt"]) ? $d["txt"] : "NONE" ) . " [". $d["type"] . "|" . $d["class"] ."] TTL=" . $d["ttl"] . "\n";					
				break;
				
				case "MX":
					echo  $d["type"] . "\t- " . $d["host"] . " - " . (isset($d["target"]) ? $d["target"] : "NONE" ) . " [". $d["type"] . "|" . $d["class"] ."] TTL=" . $d["ttl"] . " Priority=". $d["pri"] ."\n";		
				break;
				
				case "NS":
					echo $d["type"] . "\t- " . $d["host"] . " - " . (isset($d["target"]) ? $d["target"] : "NONE" ) . " [". $d["type"] . "|" . $d["class"] ."] TTL=" . $d["ttl"] . "\n";		
				break;
				
				// case "CNAME":
					
				// break;
				
				// case "PTR":
				
				// break;
				
				// case "HINFO":
				
				// break;
				
				// case "CAA":
				
				// break;
				
				case "SOA":
				// print_r($d);
					echo $d["type"] . "\t- " . $d["host"] . " - " . "mname(" . $d["mname"] . "):rname(" . $d["rname"] . ") [". $d["type"] . "|" . $d["class"] ."] TTL=" . $d["ttl"] . " Serial= ". $d["serial"] ." Min-TTL=". $d["minimum-ttl"] ." \n";
				break;
				
				// case "A6":
				
				// break;
				
				// case "SRV":
				
				// break;
				
				// case "NAPTR":
				
				// break;
				
				default: 
					print_r($d);
				break;
			}
			
			
		}
		
		// die();
		$f = fopen($wordlist, "rb");
		
		echo "\nWordlist: " . $wordlist;
		echo "\nBrute Force DNS Enumeration is starting:\n____________________________________\n";
		$start = time();
		
		if($f){
			while(!feof($f)){
				$sub = fgets($f);
				$hostname = trim($sub, "\n") . "." . $domain;
				$check = gethostbyname($hostname);
				
				if($check != $hostname){
					echo $hostname . " - " . $check . "\n";   
				}
			}
			
			fclose($f);
		}else{
			echo "Fail opening source. \n";
		}
		
		$end = time();
		$total = $end - $start;
		$hour = 0;
		$min = 0;
		$sec = $end - $start;
		
		if($total > 59){
			$min = floor($total / 60);
			$sec = $total - ($min * 60);
			
			if($min > 59){
				$hour = floor($min / 60);
				$min = $min - ($hour * 60);
			}
		}
		
		$full = $hour . "h" . $min . "m" . $sec . "s";
		
		echo "...\nScan completed in " . $full . ".";
	}
}else{
	die("Arg Error: Domain name is required. Example: 'php dnsenum.php google.com'");
}

