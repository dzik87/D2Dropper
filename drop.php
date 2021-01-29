<?php
	require 'config.php';
	global $authorized;
	$currUser 	= $_SERVER['PHP_AUTH_USER'];
	// in case someone want to play with us...
	//$authorized = array_map('strtolower', $authorized);
	//$currUser = strtolower ($currUser);
	
	if($currUser === "demo") {
		die("Dropping items for demo account is disabled.");
	}

	if (!empty($_POST['info']) && array_key_exists($currUser, $authorized)) {
		if (count($authorized[$currUser]) == 0) {
			die("No droppers assigned to your account!");
		}
		
		$items = json_decode($_POST['info']);
		
		if (empty($items)) {
			$items = json_decode(stripslashes($_POST['info']));  //fix in case of escape slashes
		}
		
		$final = array();
		$splitJob = array();
		$active = 0;
		if (file_exists('users/lastused_'.$currUser.'.txt')) {
			$active = intval(file_get_contents('users/lastused_'.$currUser.'.txt'));
		}
		if (!$active) {
			$active = 0;
		}
		
		$count_items = 0;
		$count_fg = intval($_POST['fg']);
		$gameName = $_POST['game'];
		$gamePass = $_POST['pass'];

		foreach ($items as $entry) { 
			//decode array
			$temp = json_decode($entry);
			$temp = (array)$temp;
			$temp['requester'] = $currUser;
			$temp['gameName'] = $gameName;
			$temp['gamePass'] = $gamePass;
			$temp['password'] = getPass($temp['realm'], $temp['account']);
			$temp['fgvalue'] = $count_fg;
            //$parsed = MakePickitLine($temp['itemID']);
            //$temp['dropit'] = "# ".$parsed;
            //$eval = MakeItemEval($temp['itemID']);
            //$temp['dropit'] = $eval;
			if(!isset($splitJob[$temp['account']])){
				if($active >= count($authorized[$currUser])) {
					$active = 0;
				}
				$splitJob[$temp['account']] = $authorized[$currUser][$active];
				$active++;
			}
			$temp['whoWork'] = $splitJob[$temp['account']];
			
			$finish = json_encode($temp);
			
			if (!isset($final[$temp['whoWork']])) {
				$final[$temp['whoWork']] = array();
			}
			
			array_push($final[$temp['whoWork']], $finish);
			
			$count_items++;
		}
		
		$myfile = fopen('users/lastused_'.$currUser.'.txt', "w+");
		fwrite($myfile, $active);
		fclose($myfile);
		
		foreach ($final as $who => $what) {
			$savestring = implode("\n", $what);
			$fname = "drop_".$who.".json";

			$file = fopen($fname, 'a');
					
			fwrite($file, $savestring."\n");
						
			fclose($file);
		}
		
		logSales($currUser, $count_fg, $count_items);
		print "Dropping $count_items items in $gameName//$gamePass ($count_fg fg)";
		
	} else {
		print "no items selected or you are not allowed to drop items.";
	}

	function getPass($realm, $acc) {
		try {			
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			$realmnames	= array("uswest", "useast", "asia", "europe");
			$key = array_search($realm, $realmnames);
			
			$query = /** @lang text */
				'SELECT accountPasswd FROM muleAccounts WHERE accountLogin = "'.$acc.'" AND accountRealm = '.$key;

			$results = $conn->query($query);
			
			$conn = NULL;
			
			$count = $results->fetchAll(PDO::FETCH_ASSOC);
			
			return $count[0]["accountPasswd"];
			
		} catch(PDOException $e) {
			$conn = NULL;
			return false;
		}
	}

	function MakePickitLine($id) {
		try {
            $lineB = array();
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

    		$query = 'SELECT * FROM muleItemsStats WHERE statsItemId == '.$id;
			$results = $conn->query($query);
			$conn = NULL;
			$stats = $results->fetchAll(PDO::FETCH_ASSOC);
            foreach ($stats as $stat) {
                $pickL = "[".$stat['statsName']."] == ".$stat['statsValue'];
                //print $pickL."<BR>";
                array_push($lineB, $pickL);
            }
            $pickit = implode(" && ", $lineB);
			return $pickit;

		} catch(PDOException $e) {
			$conn = NULL;
			return false;
		}
	}

    function MakeItemEval($id) {
		try {
            $lineA = array();
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

            $query = 'SELECT * FROM muleItems WHERE itemId == '.$id;
            $results = $conn->query($query);
            $first = $results->fetchAll(PDO::FETCH_ASSOC);
            foreach ($first as $info) {
                $str = "items[i].getFlags() == ".$info['itemFlag'];
                array_push($lineA, $str);
                $str = "items[i].classid == ".$info['itemClassid'];
                array_push($lineA, $str);
                $str = "items[i].quality == ".$info['itemQuality'];
                array_push($lineA, $str);
                $str = "ItemDB.getImage(items[i]) == '".$info['itemImage']."'";
                array_push($lineA, $str);
            }

            $query = 'SELECT * FROM muleItemsStats WHERE statsItemId == '.$id;
            $results = $conn->query($query);
            $conn = NULL;
            $stats = $results->fetchAll(PDO::FETCH_ASSOC);
            foreach ($stats as $stat) {
				if (is_numeric($stat['statsValue'])) { // some values are objects or undefined
                    $pickL = "items[i].getStatEx(NTIPAliasStat['".$stat['statsName']."']) == ".$stat['statsValue'];
                    array_push($lineA, $pickL);
                }
            }

            $pickit = implode(" && ", $lineA);
			return $pickit;

		} catch(PDOException $e) {
			$conn = NULL;
			return false;
		}
	}
	
	function logSales($who, $value, $count) {
		// logs fg value declared by seller
		$filename = "users/_FG_$who.log";
		$ammount = 0;
		if (file_exists($filename)) {
			$ammount = intval(file_get_contents($filename));
		}
		if (!$ammount) {
			$ammount = 0;
		}
		$ammount += $value;
		$myfile = fopen($filename, "w+");
		fwrite($myfile, $ammount);
		fclose($myfile);
		
		// logs item quantity requested by seller
		$filename = "users/_DROP_$who.log";
		$ammount = 0;
		if (file_exists($filename)) {
			$ammount = intval(file_get_contents($filename));
		}
		if (!$ammount) {
			$ammount = 0;
		}
		$ammount += $count;
		$myfile = fopen($filename, "w+");
		fwrite($myfile, $ammount);
		fclose($myfile);
	}
?>