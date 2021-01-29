<?php
require 'config.php';
$currUser = $_SERVER['PHP_AUTH_USER'];
$linecount = 0;
if(isset($authorized[$currUser])) {
	for ($i=0; $i<count($authorized[$currUser]);$i++) {
		$file="drop_".$authorized[$currUser][$i].".json";
		if(file_exists($file)) {
			$handle = fopen($file, "r");
			if ($handle) {
				while(!feof($handle)){
					$line = fgets($handle);
					$linecount++;
				}
				$linecount--;
				fclose($handle);
			}
		}
	}
}
echo $linecount;