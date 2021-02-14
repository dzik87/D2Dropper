<?php
	require 'config.php';
	require 'theme.php';
	// define global variables
	$currUser 	= $_SERVER['PHP_AUTH_USER'];
	$realms		= array("West", "East", "Asia", "Euro");
	$types		= array("SC", "HC");
	$ladder		= array("Non-Ladder", "Ladder");
	$exp		= array("Classic", "Expansion");
	$random		= array("Amazon","Assassin","Barbarian","Charsi","DeckardCain","Druid","Flavie","Gheed","Kashya","KashyaRogue","Necromancer","Paladin","Sorceress","Warriv");
	$showthat	= "";
	$itemsDB	= array();
	$charsIds	= array();
	
	if ( isset($_GET["hc"]) AND ($_GET["hc"] > 1 OR !is_numeric($_GET["hc"])) ) { $_GET["hc"] = 1; }
	if ( isset($_GET["ladder"]) AND ($_GET["ladder"] > 1 OR !is_numeric($_GET["ladder"])) ) { $_GET["ladder"] = 1; }
	if ( isset($_GET["exp"]) AND ($_GET["exp"] > 1 OR !is_numeric($_GET["exp"])) ) { $_GET["exp"] = 1; }
	if ( isset($_GET["realm"]) AND ($_GET["realm"] > 3 OR !is_numeric($_GET["realm"])) ) { $_GET["realm"] = 3; }
	
	$inGameColor   = array("","","","black","lightblue","darkblue","crystalblue","lightred","darkred","crystalred","","darkgreen","crystalgreen","lightyellow","darkyellow","lightgold","darkgold","lightpurple","","orange","white");

	// create empty db
	if (file_exists("ItemDB.s3db") == false) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			$data = [
				"PRAGMA main.page_size=4096;",
				"PRAGMA main.cache_size=10000;",
				"PRAGMA main.locking_mode=EXCLUSIVE;",
				"PRAGMA main.synchronous=NORMAL;",
				"PRAGMA main.journal_mode=WAL;",
				"PRAGMA main.temp_store = MEMORY;",
				"CREATE TABLE IF NOT EXISTS [muleAccounts] ([accountId] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT, [accountRealm] INTEGER NULL, [accountLogin] VARCHAR(32) NULL, [accountPasswd] VARCHAR(32) NULL);",
				"CREATE TABLE IF NOT EXISTS [muleChars] ([charId] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, [charAccountId] INTEGER NULL, [charName] VARCHAR(32) NULL, [charExpansion] BOOLEAN NULL, [charHardcore] BOOLEAN NULL, [charLadder] BOOLEAN NULL, [charClassId] INTEGER NULL);",
				"CREATE TABLE IF NOT EXISTS [muleItems] ([itemId] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, [itemCharId] INTEGER NULL, [itemName] VARCHAR(64) NULL, [itemType] INTEGER NULL, [itemClass] INTEGER NULL, [itemClassid] INTEGER NULL, [itemQuality] INTEGER NULL, [itemFlag] INTEGER NULL, [itemColor] INTEGER NULL, [itemImage] VARCHAR(8) NULL, [itemMD5] VARCHAR(32) NULL, [itemDescription] TEXT NULL, [itemLocation] INTEGER NULL, [itemX] INTEGER NULL, [itemY] INTEGER NULL);",
				"CREATE TABLE IF NOT EXISTS [muleItemsStats] ([statsItemId] INTEGER NULL, [statsName] VARCHAR(50) NULL, [statsValue] INTEGER NULL);",
				"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULEACCOUNTS_ACCOUNTID] ON [muleAccounts]([accountRealm] ASC, [accountLogin] ASC);",
				"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULECHARS_CHARID] ON [muleChars]([charAccountId] ASC, [charName] ASC);",
				"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULEITEMS_ITEMID] ON [muleItems]([itemId] ASC, [itemCharId] ASC);",
				"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULEITEMSSTATS_STATSITEMID] ON [muleItemsStats]([statsItemId] ASC,[statsName] ASC);",
				"CREATE TRIGGER [ON_TBL_MULEACCOUNTS_DELETE] BEFORE DELETE ON [muleAccounts] FOR EACH ROW BEGIN DELETE FROM muleChars WHERE charAccountId = OLD.accountId; END",
				"CREATE TRIGGER [ON_TBL_MULECHARS_DELETE] BEFORE DELETE ON [muleChars] FOR EACH ROW BEGIN DELETE FROM muleItems WHERE itemCharId = OLD.charId; END",
				"CREATE TRIGGER [ON_TBL_MULEITEMS_DELETE] BEFORE DELETE ON [muleItems] FOR EACH ROW BEGIN DELETE FROM muleItemsStats WHERE statsItemId = OLD.itemId; END"
			];
			
			for ($i = 0; $i < count($data); $i++) {
				$conn->query($data[$i]);
			}
			
			$conn = NULL;
		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
			return false;
		}
	}
	
	// functions
	function userAccess() {
		global $currUser, $authorized, $admin;
		
		//$authorized = array_map('strtolower', $authorized);
		//$currUser = strtolower ($currUser);
		
		if (array_key_exists($currUser, $authorized)) {
		?>
			<!-- -->
			<li><a class="exocet" id="tradelistmenu">TRADE LIST <span class="caret"></span></a></li>
			
				<div class="modal fade" id="tradeListModal" tabindex="-1" role="dialog" aria-labelledby="tradeListLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title exocet" id="tradeListLabel">TRADE LIST ITEMS</h4>
							</div>
							<div class="modal-body" id="tradelist">

							</div>
							<div class="modal-footer form-inline">
								<form class="listfunction" action="tradelistmaker.php" method="post">
									<div class="form-group pull-left">
										<input class="form-control" name="tradeinfo" type="hidden" id="listinfo" required>
										<input type="checkbox" name="charinfo" value="showinfo">Show Acc/Char Info
										<input type="checkbox" name="sorttype" value="sort">Sort By Item Type<br>
										<input class="form-control" name="maxwidth" type="text" id="listwidth" placeholder="Max Picture Width" required>
										<button id="listeem" type="submit" class="btn btn-default">Create list</button>
									</div>
								</form>
								<br>
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			<li><a class="exocet" id="opendropmenu">DROP <span class="caret"></span></a></li>
				<!-- drop modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title exocet" id="myModalLabel">ITEMS TO DROP</h4>
							</div>
							<div class="modal-body" id="droplist">
								<!-- AUTOMATIC UPDATE CONTENT -->
							</div>
							<div class="modal-footer form-inline">
								<form class="dropfunction" action="drop.php" method="post">
									<div class="form-group pull-left">
										<input class="form-control" name="info" type="hidden" id="dropitem" required>
										<input class="form-control" name="game" type="text" id="dropgmname" placeholder="gamename" required>
										<input class="form-control" name="pass" type="text" id="dropgmpass" placeholder="gamepw">
										<input class="form-control" name="fg" type="number" min="1" id="dropgmfg" style="width: 100px" placeholder="value" required>
										<button id="dropthem" type="submit" class="btn btn-default">drop</button>
									</div>
								</form>
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>	
			<?php
		}
		

		
		if (strtolower($admin) == strtolower($currUser)) {
			print '<li><a class="exocet" href="admin.php">ADMIN <span class="caret"></span></span></a></li>';
		}
	}
	
	function buildMenu() {
		//access global variables
		global $realms, $types, $random;
		
		foreach ($realms as $realmnum => $realm) {
			foreach ($types as $typenum => $type) {
				if(countChars($realmnum, $typenum, false, false)) {
					print '<li>';
					print '<a class="dropdown-toggle" id="'.$realm.''.$type.'" data-toggle="dropdown" href="#"><img src="images/icons/'.$random[rand(0, count($random)-1)].'.ico" width="20" height="20" /> '.$realm.''.$type.' <b class="caret"></b></a>';
					print '<ul class="dropdown-menu" role="menu" aria-labelledby="'.$realm.''.$type.'">';
					if(countChars($realmnum, $typenum, "1", false)) {
						print '<li role="presentation" class="disabled"><a role="menuitem" tabindex="-1">Ladder</a></li>';
						if(countChars($realmnum, $typenum, "1", "0")) {
							print /** @lang text */
								'<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?realm='.$realmnum.'&hc='.$typenum.'&ladder=1&exp=0"><img src="images/classic.png" width="18" height="18" /> Classic</a></li>';
						}
						if(countChars($realmnum, $typenum, "1", "1")) {
							print /** @lang text */
								'<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?realm='.$realmnum.'&hc='.$typenum.'&ladder=1&exp=1"><img src="images/expansion.png" width="18" height="18" /> Expansion </a></li>';
						}
						if(countChars($realmnum, $typenum, "0", false)) {
							print '<li role="presentation" class="divider"></li>';
						}
					}
					if(countChars($realmnum, $typenum, "0", false)) {
						print '<li role="presentation" class="disabled"><a role="menuitem" tabindex="-1">Non-Ladder</a></li>';
						if(countChars($realmnum, $typenum, "0", "0")) {
							print /** @lang text */
								'<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?realm='.$realmnum.'&hc='.$typenum.'&ladder=0&exp=0"><img src="images/classic.png" width="18" height="18" /> Classic</a></li>';
						}
						if(countChars($realmnum, $typenum, "0", "1")) {
							print /** @lang text */
								'<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?realm='.$realmnum.'&hc='.$typenum.'&ladder=0&exp=1"><img src="images/expansion.png" width="18" height="18" /> Expansion</a></li>';
						}							
					}
					print '</ul>';
					print '</li>';
				}
			}
		}	
	}

	function getRealmCount() {
		//access global variables
		global $realms, $types, $ladder, $exp;
		
		if (!isset($_GET["realm"]) OR !isset($_GET["hc"]) OR !isset($_GET["ladder"]) OR !isset($_GET["exp"])) {
			print '<div style="text-align: center;">';
			print "<h1 class='color8'>Welcome to item manager<br> and delivery system.<br></h1><h1 class='color1'>Please Select Realm !</h1>";
			print '<img src="images/Diablo2.png" style="-webkit-filter: drop-shadow(5px 5px 5px #222); filter: drop-shadow(10px 10px 5px #222);" >';
			print '</div>';
			return false;
		} else {

			// redefine address variables if someone try manual change
			if ($_GET["hc"] > 1     OR !is_numeric($_GET["hc"])) 		{ $_GET["hc"] 		= 1; }
			if ($_GET["ladder"] > 1 OR !is_numeric($_GET["ladder"])) 	{ $_GET["ladder"] 	= 1; }
			if ($_GET["exp"] > 1    OR !is_numeric($_GET["exp"])) 		{ $_GET["exp"] 		= 1; }
			if (!is_numeric($_GET["realm"])) { $_GET["realm"] = 1; }
			
			// define variables
			$queryR 	= $_GET["realm"];
			$queryHC	= $_GET["hc"];
			$queryLD	= $_GET["ladder"];
			$queryEXP	= $_GET["exp"];
			
			//output
			print '<div class="panel panel-default">';
				print '<div class="panel-heading">';
					print /** @lang text */
						'<h1 id="header" class="panel-title color1"><img src="images/'.$exp[$queryEXP].'.png" width="18" height="18" /> '.$realms[$queryR].''.$types[$queryHC].' '.$ladder[$queryLD].'</h1>';
				print '</div>';
			$howmany = countItems($queryR, $queryHC, $queryLD, $queryEXP);
				print "<span id='itemCounter' class='top'>You have <span class='color1'>".$howmany."</span> items in database!</span>";
				print '<div id="dropCounts"></div>';
                //print '<script type="text/javascript">CheckDrops();</script>';
				print '<br><br>';
				print '<form class="input-group searchform col-sm-12" id="searchform">'; // action="'.$_SERVER['REQUEST_URI'].'" method="post"
					print '<div class="col-sm-12">';
						print '<div class="input-group">';
							print '<span class="input-group-btn">';
								print '<div class="btn btn-default col-md-12">pack: <select id="sloadlist" name="sloadlist" style="width:120px!important;min-width:120px;max-width:120px;"><option></option>';
								foreach(glob(dirname(__FILE__) . '/savedlist/*') as $filename){
									$filename = basename($filename);
									echo "<option value='" . $filename . "'>".explode(".txt", $filename)[0]."</option>";
								}
								print '</select></div>';
							print '</span>';
							print '<span class="input-group-btn">';
								print '<div class="btn btn-default col-md-12">quality: <select id="search_parameter" name="itemtype" style="width:120px!important;min-width:120px;max-width:120px;"><option></option><option>white</option><option>magic</option><option>set</option><option>rare</option><option>unique</option><option>craft</option><option>runes</option><option>runeword</option><option>torch</option><option>annihilus</option><option>uberkeys</option><option>organs</option></select></div>';
							print '</span>';
							print '<span class="input-group-btn">';
								print '<div class="btn btn-default col-md-12">type: <select name="itemtype2" style="width:120px!important;min-width:120px;max-width:120px;"><option></option><option>weapons</option><option>ring</option><option>amulet</option><option>jewel</option><option>helm</option><option>circlet</option><option>armor</option><option>shield</option><option>pelt</option><option>auricshields</option><option>voodooheads</option><option>boots</option><option>gloves</option><option>belt</option><option>small charm</option><option>large charm</option><option>grand charm</option></select></div>';
							print '</span>';
                            print '<span class="input-group-btn">';
                                print '<div class="btn btn-default col-md-12">ethereal: <select name="eth" style="width:120px!important;min-width:120px;max-width:120px;"><option></option><option>true</option><option>false</option></select></div>';
                            print '</span>';
                            print '<span class="input-group-btn">';
                                print '<div class="btn btn-default col-md-12">identified: <select name="identified" style="width:120px!important;min-width:120px;max-width:120px;"><option></option><option>true</option><option>false</option></select></div>';
                            print '</span>';
                            print '<span class="input-group-btn">';
                                print '<div class="btn btn-default col-md-12">color: <select name="colorIt" style="width:120px!important;min-width:120px;max-width:120px;"><option></option>';
									global $inGameColor;
									for($c = 0; $c<count($inGameColor); $c++) {
										if($inGameColor[$c] != "") {
											print '<option>'.$inGameColor[$c].'</option>';
										}
									}
								print '</select></div>';
                            print '</span>';
                            print '<span class="input-group-btn">';
                                print '<div class="btn btn-default col-md-12">limit results: <select name="itemlimit" style="width:120px!important;min-width:120px;max-width:120px;"><option>100</option><option>200</option><option>300</option><option>400</option><option>500</option><option>1000</option><option>2000</option><option>5000</option></select></div>';
                            print '</span>';
						print '</div>';
                    print '</div>';
                    print '<div class="col-sm-12">';
						print '<div class="input-group">';
							print '<input type="text" class="form-control" placeholder="Search for..." id="searchtext" name="search" required>';
							print '<span class="input-group-btn">';
								print '<button class="btn btn-default searchbut" type="button" url="show.php?realm='.$queryR.'&hc='.$queryHC.'&ladder='.$queryLD.'&exp='.$queryEXP.'">find items</button>';
							print '</span>';
						print '</div>';
                    print '</div>';
				print '</form>';
				print '<br>';
			print '</div>';
			return true;
		}
	}
	
	function getCount($queryR, $queryHC, $queryLD, $queryEXP, $count = NULL) {
		global $itemsDB;
		global $noAccess;
		global $currUser;
		
		if (array_key_exists($currUser, $noAccess)) {
			for ($i = 0; $i < count($noAccess[$currUser]); $i++) {
				if ($queryR   == (string)$noAccess[$currUser][$i][0] &&
					$queryHC  == (string)$noAccess[$currUser][$i][1] &&
					$queryLD  == (string)$noAccess[$currUser][$i][2] &&
					$queryEXP == (string)$noAccess[$currUser][$i][3]) {
					
					return 0;
				}
			}
		}
		
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB	= "";
			$tempC	= "";
			$tempD	= "";
			
			if ($queryEXP === "0" OR $queryEXP === "1") {
				$tempB = " AND charExpansion = ".$queryEXP;
			}
			if ($queryLD === "0" OR $queryLD === "1") {
				$tempC = " AND charLadder = ".$queryLD;
			}

			$sql = "";
			switch (strtolower($count)) {
					case "chars":
						$sql = 'SELECT COUNT() AS "count" FROM muleChars LEFT JOIN muleAccounts ON charAccountId = accountId WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'';
						break;
					case "items":
						$sql = 'SELECT COUNT() AS "count" FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountId WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'';
						break;
					default:
						$conn = NULL;
						return false;
			}

			$results = $conn->query($sql);
			$conn = NULL;
			$itemsDB = $results->fetchAll(PDO::FETCH_ASSOC);

			return $itemsDB[0]["count"];
		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
			return false;
		}
	}

	function countItems($queryR, $queryHC, $queryLD, $queryEXP) {
		return getCount($queryR, $queryHC, $queryLD, $queryEXP, "items");
	}

	function countChars($queryR, $queryHC, $queryLD, $queryEXP) {
		return getCount($queryR, $queryHC, $queryLD, $queryEXP, "chars");
	}

	function getAccounts() {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			// define variables
			$queryR 	= $_GET["realm"];
			$queryHC	= $_GET["hc"];
			$queryLD	= $_GET["ladder"];
			$queryEXP	= $_GET["exp"];
			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB 	= " AND charExpansion = ".$queryEXP;
			$tempC 	= " AND charLadder = ".$queryLD;
			$tempD	= " GROUP BY accountLogin ORDER BY accountId DESC";
			//$tempD	= " GROUP BY accountLogin";
			
			//$select	= "itemName, itemQuality, itemImage, itemMD5, itemFlag, itemDescription";
			$select	= "accountId, accountLogin";
			
			$query = 'SELECT '.$select.' FROM muleAccounts LEFT JOIN muleChars ON charAccountId = accountId WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'';

			$results = $conn->query($query);
			$conn = NULL;
			
			$accounts = $results->fetchAll(PDO::FETCH_ASSOC);
			
			print '<ul class="list-group">';
			$charLink = 'chars.php?realm=' . $queryR . '&hc=' . $queryHC . '&ladder=' . $queryLD . '&exp=' . $queryEXP . '&accountid=';
			
			foreach ($accounts as $nr => $account) {
				print '<li class="list-group-item">';
				print '<div class="mainmenu" data-toggle="collapse" data-target="#a'.$account["accountId"].'" data-aid="'.$account["accountId"].'" data-link="'.$charLink.$account["accountId"].'">'.$account["accountLogin"].'</div>';
				print '<div id="a' . $account["accountId"] . '">';
				print '</div></li>';
			}
			
			print '</ul>';
			
		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
		}
	}
	
	function getChars($accid) {
		global $charsIds;
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			
			// define variables
			$queryR 	= $_GET["realm"];
			$queryHC	= $_GET["hc"];
			$queryLD	= $_GET["ladder"];
			$queryEXP	= $_GET["exp"];
			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB 	= " AND charExpansion = ".$queryEXP;
			$tempC 	= " AND charLadder = ".$queryLD;
			$select	= "charId, charName, charClassId";
			
			$query = 'SELECT '.$select.' FROM muleChars WHERE charAccountId = '.$accid.' '.$tempA.' '.$tempB.' '.$tempC.' ';

			$results = $conn->query($query);
						
			$chars = $results->fetchAll(PDO::FETCH_ASSOC);
			
			$conn = NULL;
			
			$classes 	=	array("Amazon", "Sorceress", "Necromancer", "Paladin", "Barbarian", "Druid", "Assassin");
			
			print '<ul id="acc'.$accid.'" class="list-unstyled">';
			
			foreach ($chars as $nr => $char) {
				array_push($charsIds, $char["charId"]);
				global $showthat;
				
				if (isset($_GET["charid"])) {
					if ($_GET["charid"] == $char["charId"]) {
						$showthat = "acc".$accid;
					}
				}
				
				if ($showthat == "") {
					$showthat = "acc".$accid;
				}
				
				print /** @lang text */
					'<li><a href="show.php?realm='.$queryR.'&hc='.$queryHC.'&ladder='.$queryLD.'&exp='.$queryEXP.'&charid='.$char["charId"].'" class="submenu"><img src="images/icons/'.$classes[$char["charClassId"]].'.ico" width="16" height="16" /> '.$char["charName"].' <span class="label alert-warning pull-right">'.countItemsOnChar($char["charId"]).'</span></a></li>';
			}
			print '</ul>';
			
		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
		}
	}
	
	function countItemsOnChar($charId) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			
			$query = /** @lang text */
				'SELECT COUNT() AS "count" FROM muleItems Where itemCharId = '.$charId;

			$results = $conn->query($query);
			
			$conn = NULL;
			
			$count = $results->fetchAll(PDO::FETCH_ASSOC);
			
			return $count[0]["count"];
			
		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
			return false;
		}
	}
	
	function showCurrentItems() {
		global $charsIds;
		global $inGameColor;
		$qualityColor  = array("", "colorb", "colorb", "colorb", "color3", "color2", "color9", "color4", "color8");
		//$inGameColor   = array("","","","black","lightblue","darkblue","crystalblue","lightred","darkred","crystalred","","darkgreen","crystalgreen","lightyellow","darkyellow","lightgold","darkgold","lightpurple","","orange","white");
		$resultcount   = "";

		if(isset($_GET["charid"])) {
			$show = getItemsFromDb($_GET["charid"]);
		}
		if(isset($charsIds[0]) AND !isset($_GET["charid"])) {
			$show = getItemsFromDb($charsIds[0]);
		}
		
		if (isset($_POST["sloadlist"]) && $_POST["search"] === "") {
			$show = getItemsFromDb(1, $_POST["sloadlist"]);
			$resultcount = " ".count($show);
		}
		
		else if (isset($_POST["search"])) {
			$show = getItemsFromDb(1);
			$resultcount = " ".count($show);
		}
		
		print '<div class="panel-heading">';
			//print '<h1 class="panel-title">Items list ('.$resultcount.' '.getCurrentName().' ) <span class="showhide color8 pull-right">hide equiped</span></h1>';
			print
                '<h1 class="panel-title">Items list ('.$resultcount.' '.getCurrentName().' )
			    <div class="form-inline pull-right" style="margin-top:-7px;">
                    <input class="form-control markall" style="width:100px;" type="number" id="massMark" required>
                    <button onclick="MarkThem()" class="btn btn-default markall">mark from top</button>
                    <button onclick="ClearAll()" class="btn btn-default markall">clear all</button>
                    <button class="btn btn-default markall showhide">hide equiped</button>
                </div>
			</h1>';
		print '</div>';
		print '<table id="itemstable" class="table diablo">';
			print '<thead><tr>';
            if (isset($_POST["search"]) AND !($_POST["itemtype"] == "torch" OR $_POST["itemtype"] == "annihilus" OR $_POST["itemtype"] == "rare")) {
				print '<th width="20%" class="text-left exocet"><strong>CHAR</strong></th>';
			}
			if (isset($_POST["itemtype"]) AND ($_POST["itemtype"] == "torch" OR $_POST["itemtype"] == "annihilus")) {
                if($_POST["itemtype"] == "torch"){
                    print '<th width="15%" class="text-center exocet"><strong>CLASS</strong></th>';
                }
				print '<th width="15%" class="text-center exocet"><strong>STAT</strong></th>';
				print '<th width="15%" class="text-center exocet"><strong>RES</strong></th>';
                if ($_POST["itemtype"] == "annihilus"){
                    print '<th width="15%" class="text-center exocet"><strong>EXP</strong></th>';
                }
			} else if (isset($_POST["itemtype2"]) AND $_POST["itemtype2"] == "grand charm") {
				print '<th width="15%" class="text-center exocet"><strong>SKIN</strong></th>';
                print '<th width="15%" class="text-center exocet"><strong>HP</strong></th>';
			} else {
				print '<th width="15%" class="text-center exocet"><strong>ED</strong></th>';
                print '<th width="15%" class="text-center exocet"><strong>SOCKETS</strong></th>';
                if (isset($_POST["itemtype"]) AND $_POST["itemtype"] == "rare") {
                    print '<th width="15%" class="text-center exocet"><strong>COLOR</strong></th>';
                }
			}
			print '<th width="*" class="exocet"><strong>NAME</strong></th>';
			print '</tr></thead>';
			
			print '<tbody>';

			foreach ($show as $nr => $item) {
				$desc	    = $item["itemDescription"];
				$colOne		= checkStat($item["itemId"], "enhanceddefense");
				$colTwo 	= checkStat($item["itemId"], "sockets");
                $colThree   = "";
				$itCo       = "";
                $colInd     = "";

				if($item["itemColor"] != -1 and $item["itemQuality"] == 6) {
					$colInd = $item["itemColor"];
					$itCo   = "<br><br> color: ".$inGameColor[$colInd];

				}
				if ($colOne == "") {
					$colOne	= checkStat($item["itemId"], "enhanceddamage");
					if ($colOne != "") {
						$colOne = $colOne."% (dmg)";
					}					
				} else if ($colOne != "") {
					$colOne = $colOne."% (def)";
				}
				
				if (isset($_POST["itemtype2"]) AND $_POST["itemtype2"] == "grand charm") {
					//$colOne = $item["itemImage"];
					$colOne = "<img src='images/items/".$item["itemImage"].".png'>";
					$colTwo	= checkStat($item["itemId"], "maxhp");
				}
				
				if ($colTwo != "") {
					$colTwo = $colTwo." sox";
				}
				else {
					$colTwo = "&nbsp;";
				}
				if (isset($_POST["itemtype"]) AND ($_POST["itemtype"] == "torch" OR $_POST["itemtype"] == "annihilus")) {
					$colOne		= checkStat($item["itemId"], "strength");
					if($colOne == "") {
                        $colOne = "unid";
                    }
					$colTwo 	= checkStat($item["itemId"], "fireresist");
                    if($colTwo == "") {
                        $colTwo = "unid";
                    }
                    if($_POST["itemtype"] == "annihilus") {
                        $colThree 	= checkStat($item["itemId"], "itemaddexperience");
                        if($colThree == "") {
                            $colThree = "unid";
                        }
                    }
					if($_POST["itemtype"] == "torch") {
						// thank you to MrSithy <3
						if($colThree = checkStat($item["itemId"], "itemaddsorceressskills")) {
							$colThree = "sorceress";
						}else if($colThree = checkStat($item["itemId"], "itemaddbarbarianskills")) {
							$colThree = "barbarian";
						}else if($colThree = checkStat($item["itemId"], "itemaddnecromancerskills")) {
							$colThree = "necromancer";
						}else if($colThree = checkStat($item["itemId"], "itemaddassassinskills")) {
							$colThree = "assassin";
						}else if($colThree = checkStat($item["itemId"], "itemaddamazonskills")) {
							$colThree = "amazon";
						}else if($colThree = checkStat($item["itemId"], "itemadddruidskills")) {
							$colThree = "druid";
						}else if($colThree = checkStat($item["itemId"], "itemaddpaladinskills")) {
							$colThree = "paladin";
						}else{
							$colThree = "unid";
						}
					}
				}
				
				$trclass = "loc".$item["itemLocation"];
				
				$realmnames	= array("uswest", "useast", "asia", "europe");
				$realmname	= $realmnames[$item["accountRealm"]];
				
				$trinfo = ' drImage="'.$item["itemImage"].'" drID="'.$item["itemId"].'" dritemid="itemid'.$item["itemId"].'" draccount="'.$item["accountLogin"].'" dritemtype="'.$item["itemType"].'" drchar="'.$item["charName"].'" drmd5="'.$item["itemMD5"].'" drrealm="'.$realmname.'" drname="'.$item["itemName"].'"';
									
				print '<tr'.$trinfo.' class="'.$trclass.' item">';
				if (isset($_POST["search"]) AND !($_POST["itemtype"] == "torch" OR $_POST["itemtype"] == "annihilus" OR $_POST["itemtype"] == "rare")) {
					print '<td class="text-left"><b>'.$item["charName"].'</b></td>';
				}
                if(isset($_POST["itemtype"]) AND $_POST["itemtype"] == "torch"){
                    print '<th width="15%" class="text-center exocet"><strong>'.$colThree.'</strong></th>';
                }
				print '<td class="text-center"><b>'.$colOne.'</b></td>';
				print '<td class="text-center"><b>'.$colTwo.'</b></td>';
                if(isset($_POST["itemtype"]) AND $_POST["itemtype"] == "annihilus"){
                    print '<td class="text-center"><b>'.$colThree.'</b></td>';
                }
                if (isset($_POST["itemtype"]) AND $_POST["itemtype"] == "rare") {
                    print '<th width="15%" class="text-center exocet"><strong>'.$inGameColor[$colInd].'</strong></th>';
                }
				$tooltip = /** @lang text */
					'<center>&lt;img src=&quot;images/items/'.$item["itemImage"].'.png&quot;&gt; <br>'.$desc.''.$itCo.'</center>';
				
				print '<td><div class="'.$qualityColor[$item["itemQuality"]].' show-tooltip form-inline" title="'.$tooltip.'"><b>'.$item["itemName"].'</b></div></td>';

				print '</tr>';
			}
			print '</tbody>';
			print '<tfoot><tr>';
            if (isset($_POST["search"]) AND !($_POST["itemtype"] == "torch" OR $_POST["itemtype"] == "annihilus" OR $_POST["itemtype"] == "rare")) {
				print '<td width="20%" class="text-left exocet"><strong>CHAR</strong></td>';
			}
			if (isset($_POST["itemtype"]) AND ($_POST["itemtype"] == "torch" OR $_POST["itemtype"] == "annihilus")) {
                if($_POST["itemtype"] == "torch"){
                    print '<th width="15%" class="text-center exocet"><strong>CLASS</strong></th>';
                }
				print '<th width="15%" class="text-center exocet"><strong>STAT</strong></th>';
				print '<th width="15%" class="text-center exocet"><strong>RES</strong></th>';
                if($_POST["itemtype"] == "annihilus"){
                    print '<th width="15%" class="text-center exocet"><strong>EXP</strong></th>';
                }
			} else {
				print '<th width="15%" class="text-center exocet"><strong>ED</strong></th>';
                print '<th width="15%" class="text-center exocet"><strong>SOCKETS</strong></th>';
                if (isset($_POST["itemtype"]) AND $_POST["itemtype"] == "rare") {
                    print '<th width="15%" class="text-center exocet"><strong>COLOR</strong></th>';
                }
			}
			print '<td width="*" class="exocet"><strong>NAME</th>';
			print '<tr></tfoot>';	
		print '</table>';
	}
	
	function getItemsFromDb($charid, $packname = null) {
		global $inGameColor;
		$selectinfo = "accountLogin, accountRealm, charName, itemId, itemName, itemType, itemQuality, itemImage, itemDescription, itemMD5, itemLocation, itemColor";
		
		if ($packname) {
			$packitems = [];
			$handle = fopen("savedlist/".$packname, "r");
			while(!feof($handle)) array_push($packitems, json_decode(fgets($handle), true));
			fclose($handle);
			
			$count = [];
			
			for ($i = 0; $i < count($packitems); $i++) {
				try {
					$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
					
					$queryR 	= $_GET["realm"];
					$queryHC	= $_GET["hc"];
					$queryLD	= $_GET["ladder"];
					$queryEXP	= $_GET["exp"];
					
					$tempA	= " AND charHardcore = ".$queryHC;
					$tempB 	= " AND charExpansion = ".$queryEXP;
					$tempC 	= " AND charLadder = ".$queryLD;
					$tempD	= "";
					
					$pieces = explode(",", $packitems[$i]['keyword']);
					
					for ($x = 0; $x < count($pieces); $x++) {
						$valid = str_replace("'", "_", $pieces[$x]);
						if (substr($valid, 0, 1) === "!") {
							$one = " AND lower(itemDescription) NOT LIKE '%".substr($valid, 1, strlen($valid))."%'";
						} else {
							$one = " AND lower(itemDescription) LIKE '%".$valid."%'";
						}
						$tempD .= $one;
					}
					
					global $currUser;
					global $noAccessItems;
					global $itemLists;
					
					if (array_key_exists($currUser, $noAccessItems)) {
						for ($x = 0; $x < count($noAccessItems[$currUser]); $x++) {
							$hideList = $noAccessItems[$currUser][$x];
							
							for ($z = 0; $z < count($itemLists[$hideList]); $z++) {
								$tempD .= " AND NOT (" . $itemLists[$hideList][$z] . ")";
							}
						}
					}

					$filterBy	= "";
					$filterBy2	= "";
					$orderBy	= "";
					$orderBy2	= "";
					$colorF		= "";
					
					$limit		= "LIMIT " . (string)$packitems[$i]['occu'];
					
					$query = /** @lang text */
						'SELECT '.$selectinfo.' FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountID WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.' '.$colorF.' '.$filterBy.' '.$filterBy2.' '.$orderBy.' '.$orderBy2.' '.$limit.';';
					
					$results	= $conn->query($query);
					$conn		= NULL;
					$rows 		= $results->fetchAll(PDO::FETCH_ASSOC);
					
					foreach ($rows as $row) array_push($count, $row);
					
				} catch(PDOException $e) {
					$conn = NULL;
					print 'Exception : '.$e->getMessage();
					return false;
				}
			}

			return $count;
		}
		
		if (!isset($_POST["search"])) {
			try {
				$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
				
				$query = /** @lang text */
                    //'SELECT '.$selectinfo.' FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountID WHERE itemCharId = '.$charid.' ORDER BY itemType DESC';
                    'SELECT '.$selectinfo.' FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountID WHERE itemCharId = '.$charid.'';

				global $currUser;
				global $noAccessItems;
				global $itemLists;
				
				if (array_key_exists($currUser, $noAccessItems)) {
					for ($x = 0; $x < count($noAccessItems[$currUser]); $x++) {
						$hideList = $noAccessItems[$currUser][$x];
						
						for ($i = 0; $i < count($itemLists[$hideList]); $i++) {
							$query .= " AND NOT (" . $itemLists[$hideList][$i] . ")";
						}
					}
				}
				$query .= " LIMIT " . countItemsOnChar($charid);

				$results = $conn->query($query);
				
				$conn = NULL;
				
				$count = $results->fetchAll(PDO::FETCH_ASSOC);
				
				return $count;
				
			} catch(PDOException $e) {
				$conn = NULL;
				print 'Exception : '.$e->getMessage();
                return false;
			}
		} else {
			try {
				$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
				
				$queryR 	= $_GET["realm"];
				$queryHC	= $_GET["hc"];
				$queryLD	= $_GET["ladder"];
				$queryEXP	= $_GET["exp"];
				
				$tempA	= " AND charHardcore = ".$queryHC;
				$tempB 	= " AND charExpansion = ".$queryEXP;
				$tempC 	= " AND charLadder = ".$queryLD;
				$tempD	= "";
				
				$pieces = explode(",", $_POST["search"]);
				
				for ($x = 0; $x < count($pieces); $x++) {
					$valid = str_replace("'", "_", $pieces[$x]);
					$one = " AND lower(itemDescription) LIKE '%".$valid."%'";
					$tempD .= $one;
				}
				
				//" AND NOT (itemQuality == 7 AND itemClassid == 603)"
				
				global $currUser;
				global $noAccessItems;
				global $itemLists;
				
				if (array_key_exists($currUser, $noAccessItems)) {
					for ($x = 0; $x < count($noAccessItems[$currUser]); $x++) {
						$hideList = $noAccessItems[$currUser][$x];
						
						for ($i = 0; $i < count($itemLists[$hideList]); $i++) {
							$tempD .= " AND NOT (" . $itemLists[$hideList][$i] . ")";
						}
					}
				}
				
				$types = array();
					$types['white'] = "AND itemQuality < 4 AND (\"itemFlag\" & 0x4000000) == 0 AND (\"itemClassid\" NOT BETWEEN 610 AND 642)";
					$types['magic'] = "AND itemQuality == 4";
					$types['set'] = "AND itemQuality == 5";
					$types['rare'] = "AND itemQuality == 6";
					$types['unique'] = "AND itemQuality == 7";
					$types['craft'] = "AND itemQuality == 8";
					$types['torch'] = "AND itemQuality == 7 AND itemClassid == 604";
					$types['annihilus'] = "AND itemQuality == 7 AND itemClassid == 603";
					$types['runeword'] = "AND (\"itemFlag\" & 0x4000000) == 0x4000000";
					$types['runes'] = "AND \"itemClassid\" BETWEEN 610 AND 642";
					$types['uberkeys'] = "AND \"itemClassid\" BETWEEN 647 AND 649";
					$types['organs'] = "AND \"itemClassid\" BETWEEN 650 AND 652";

				$filterBy = "";
				if (!empty($_POST["itemtype"])) {
					$filterBy = $types[$_POST["itemtype"]];
				}
				
				$types2 = array();
					$types2["weapons"] = "AND itemType IN (24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 42, 43, 44, 45, 46, 47, 48, 49, 56, 57, 67, 68, 85, 86, 87, 88)";
					$types2["ring"] = "AND itemType == 10";
					$types2["amulet"] = "AND itemType == 12";
					$types2["jewel"] = "AND itemType == 58";
					$types2["helm"] = "AND itemType == 37";
					$types2["circlet"] = "AND itemType == 75";
					$types2["armor"] = "AND itemType == 3";
					$types2["shield"] = "AND itemType == 2";
					$types2["pelt"] = "AND itemType == 72";
					$types2["auricshields"] = "AND itemType == 70";
					$types2["voodooheads"] = "AND itemType == 69";
					$types2["boots"] = "AND itemType == 15";
					$types2["gloves"] = "AND itemType == 16";
					$types2["belt"] = "AND itemType == 19";
					$types2["small charm"] = "AND itemType == 82";
					$types2["large charm"] = "AND itemType == 83";
					$types2["grand charm"] = "AND itemType == 84";
					
				$filterBy2 = "";
				if (!empty($_POST["itemtype2"])) {
					$filterBy2 = $types2[$_POST["itemtype2"]];
				}

				$orderBy = "";
				if (!empty($_POST["eth"])) {
					if ($_POST["eth"] == "true") {
						$orderBy = "AND (\"itemFlag\" & 0x400000) == 0x400000";
					}
					if ($_POST["eth"] == "false") {
						$orderBy = "AND (\"itemFlag\" & 0x400000) == 0";
					}
				}

				$orderBy2 = "";
				if (!empty($_POST["identified"])) {
					if ($_POST["identified"] == "true") {
						$orderBy2 = "AND (\"itemFlag\" & 0x10) == 0x10";
					}
					if ($_POST["identified"] == "false") {
						$orderBy2 = "AND (\"itemFlag\" & 0x10) == 0";
					}
				}

				//$limit = "ORDER BY itemType DESC LIMIT ".$_POST["itemlimit"];

				$limit = "LIMIT ".$_POST["itemlimit"];
				
				$colorF = "";
				if (!empty($_POST["colorIt"]) AND !empty($_POST["itemtype"]) AND $_POST["itemtype"] == "rare") {
					$colorIdx = array_search($_POST["colorIt"], $inGameColor);
					$colorF = "AND itemColor == ".$colorIdx."";
				}
				
				$query = /** @lang text */
					'SELECT '.$selectinfo.' FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountID WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.' '.$colorF.' '.$filterBy.' '.$filterBy2.' '.$orderBy.' '.$orderBy2.' '.$limit.';';
				
				$results = $conn->query($query);
				
				$conn = NULL;
				
				$count = $results->fetchAll(PDO::FETCH_ASSOC);
				
				return $count;
				
			} catch(PDOException $e) {
				$conn = NULL;
				print 'Exception : '.$e->getMessage();
				return false;
			}	
		}
	}
	
	function checkStat($itemid, $what) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			
			$query = /** @lang text */
				'SELECT statsValue FROM muleItemsStats WHERE statsItemId = "'.$itemid.'" AND statsName = "'.$what.'"';

			$results = $conn->query($query);
			
			$conn = NULL;
			
			$count = $results->fetchAll(PDO::FETCH_ASSOC);
			
			$ed	= "";
			
			if(isset($count[0]["statsValue"])){
				$ed = $count[0]["statsValue"];
			}
			
			return $ed;
			
		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
			return false;
		}
	}
	
	function getCurrentName() {
		if (!isset($_POST["search"])) {		
			try {
				global $charsIds;
				
				if(isset($_GET["charid"])) {
					$charid = $_GET["charid"];
				}
				if(isset($charsIds[0]) AND !isset($_GET["charid"])) {
					$charid = $charsIds[0];
				}
				
				$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
				
				$query = /** @lang text */
					'SELECT charName FROM muleChars WHERE charId = '.$charid;

				$results = $conn->query($query);
				
				$conn = NULL;
				
				$count = $results->fetchAll(PDO::FETCH_ASSOC);
				
				$name	= "";
				
				if(isset($count[0]["charName"])){
					$name = $count[0]["charName"];
				}
				
				return $name;
				
			} catch(PDOException $e) {
				$conn = NULL;
				print 'Exception : '.$e->getMessage();
				return false;
			}	
		} else {
			$name = "search results";
			return $name;
		}
	}
?>
