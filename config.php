<?php
	// Master admin. Can be only one.
	$admin			= "dzik";
	
	// Array of usernames what have access to drop menu, each entry define dropper profiles to be used bythat user.
	$authorized 	= array(
		"dzik" 		=> array("dropper1", "dropper2", "dropper3", "dropper4")
	);
	
	$showAccounts	= true;
	
	//Example to block useast expansion softcore ladder accs on this acc.
	//realm/hardcore/ladder/expansion
	//realms -> 0 = west, 1 = east, 2 = euro, 3 = asia
	//[1,0,1,1]
	
	$noAccess = array(
		"dzik" => [
			[1,1,1,1],
		],
	);
	
	$noAccessItems = array(
		//"dzik" => ["all minus annis and sojs"],
	);
	
	$itemLists = array(
		"annis"	=> ["itemQuality == 7 AND itemClassid == 603"],
		"sojs"	=> ["itemName LIKE '%the stone of jordan ring%'"],
		
		"all minus annis and sojs"	=> ["((itemQuality != 7 AND itemClassid != 603) OR itemName NOT LIKE '%the stone of jordan ring%')"],

		"hrs" => [
			"itemClassid == 639", //Ber
			"itemClassid == 640", //Jah
		],
	);
?>
	