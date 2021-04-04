<?php
	$mystring = $_SERVER["HTTP_REFERER"];
	$pos = strpos($mystring, 'admin.php');
	if ($pos !== false) {
		if(isset($_POST["fun"])) {
			$function = $_POST["fun"];
			$argument = $_POST["arg"];
			$exp = false;
			if(isset($_POST["exp"])){
				$exp = $_POST["exp"];
			}

			if($function === "finishLadder") {
				print '<div class="panel-heading"><h2 class="panel-title text-center">Finish ladder - convert all characterd to non ladder.</h2></div>';
				print '<div id="textoutput">';
					print '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> This cannot be undone! Please think twice before converting characters to non ladder!</div>';
					print 'Use button below to convert all characters on all realms to non ladder.';
					print '<br><br>';
					print '<div style="text-align: center;"><a function="finishConfirm" arg="yes" class="exotec confirm">FINISH LADDER</a></div>';
				print '</div>';
				
			} else if($function === "finishConfirm") {
				print '<div class="panel-heading"><h2 class="panel-title text-center">Finish ladder - convert all characterd to non ladder.</h2></div>';
				if (Convert()) {
					print '<div id="textoutput">';
						print '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Success!.</div>';
					print '</div>';
				} else {
					print '<div id="textoutput">';
						print '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Failed!</div>';
					print '</div>';
				}
				
			} else if($function === "deleteEquipped") {
				print '<div class="panel-heading"><h2 class="panel-title text-center">Delete Equiped - Delete all equipped items.</h2></div>';
				print '<div id="textoutput">';
					print '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> This cannot be undone! Please think twice before deleting!</div>';
					print 'Use button below to delete all equipped items from database.<br>';
					print 'If you don\'t want log equipped items at all - you can disable it in <strong>/kolbot/libs/ItemDB.js</strong> and set skipEquiped to <strong>true</strong> on very top of file (line 12).';
					print '<br><br>';
					print '<div style="text-align: center;"><a function="deleteConfirm" arg="yes" class="exotec confirm">DELETE EQUIPPED</a></div>';
				print '</div>';
				
			} else if($function === "deleteConfirm") {
				print '<div class="panel-heading"><h2 class="panel-title text-center">Delete Equiped - Delete all equipped items.</h2></div>';
				if (DeleteEquip()) {
					print '<div id="textoutput">';
						print '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Success!.</div>';
					print '</div>';
				} else {
					print '<div id="textoutput">';
						print '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Failed!</div>';
					print '</div>';
				}
				
			} else if($function === "listTorch") {
				$modes = array("SOFTCORE", "HARDCORE");
				print '<div class="panel-heading"><h2 class="panel-title text-center">Hellfire Torch ('.$modes[$argument].')</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet"> </th>';
				print '<th width="*" class="text-center exocet"><strong>EuroL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EuroNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaNL</strong></th>';
				print '</tr></thead>';
				
				$array = array("Amazon", "Sorceress", "Necromancer", "Paladin", "Barbarian", "Druid", "Assassin", "Unidentified");
				for ($y = 0; $y < count($array); $y++) {
					print '<tr>';
					print '<td width="30%" class="text-left">'.$array[$y].'</td>';
					print '<td width="*" class="text-center">0</td>';
					print '<td width="*" class="text-center">0</td>';
					print '<td width="*" class="text-center">0</td>';
					print '<td width="*" class="text-center">0</td>';
					print '<td width="*" class="text-center">0</td>';
					print '<td width="*" class="text-center">0</td>';
					print '<td width="*" class="text-center">0</td>';
					print '<td width="*" class="text-center">0</td>';
					print '</tr>';
				}
				
				print '</table>';
				
			} else if($function === "listAnni") {
				$modes = array("SOFTCORE", "HARDCORE");
				print '<div class="panel-heading"><h2 class="panel-title text-center">Annihilus ('.$modes[$argument].')</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet"> </th>';
				print '<th width="*" class="text-center exocet"><strong>EuroL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EuroNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaNL</strong></th>';
				print '</tr></thead>';
				
				$array = array("Perfect", "+20 To All Attributes", "All Resistances +20", "10% To Experience Gained", "Unidentified", "All");
				for ($y = 0; $y < count($array); $y++) {
					print '<tr>';
					print '<td width="30%" class="text-left">'.$array[$y].'</td>';
					print '<td width="*" class="text-center">'.Anni(3, $argument, 1, 1, $y).'</td>';
					print '<td width="*" class="text-center">'.Anni(3, $argument, 0, 1, $y).'</td>';
					print '<td width="*" class="text-center">'.Anni(1, $argument, 1, 1, $y).'</td>';
					print '<td width="*" class="text-center">'.Anni(1, $argument, 0, 1, $y).'</td>';
					print '<td width="*" class="text-center">'.Anni(0, $argument, 1, 1, $y).'</td>';
					print '<td width="*" class="text-center">'.Anni(0, $argument, 0, 1, $y).'</td>';
					print '<td width="*" class="text-center">'.Anni(2, $argument, 1, 1, $y).'</td>';
					print '<td width="*" class="text-center">'.Anni(2, $argument, 0, 1, $y).'</td>';
					print '</tr>';
				}
				
				print '</table>';
				
			} else if($function === "listPandemonium") {
				$modes = array("SOFTCORE", "HARDCORE");
				print '<div class="panel-heading"><h2 class="panel-title text-center">Pandemonium Event ('.$modes[$argument].')</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet"> </th>';
				print '<th width="*" class="text-center exocet"><strong>EuroL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EuroNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaNL</strong></th>';
				print '</tr></thead>';
				
				$array = array("pk1", "pk2", "pk3", "dhn", "bey", "mbr");
				$array2 = array("Key of Terror", "Key of Hate", "Key of Destruction", "Diablo's Horn", "Baal's Eye", "Mephisto's Brain");
				for ($y = 0; $y < count($array); $y++) {
					print '<tr>';
					// realms: { "uswest": 0, "useast": 1, "asia": 2, "europe": 3 },
					// Runes($queryR, $queryHC, $queryLD, $queryEXP, $itemId)
					print '<td width="30%" class="text-left rune"><img src="images/items/'.$array[$y].'.png"> '.$array2[$y].'</td>';
					print '<td width="*" class="text-center">'.Runes(3, $argument, 1, $exp, 647 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(3, $argument, 0, $exp, 647 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(1, $argument, 1, $exp, 647 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(1, $argument, 0, $exp, 647 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(0, $argument, 1, $exp, 647 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(0, $argument, 0, $exp, 647 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(2, $argument, 1, $exp, 647 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(2, $argument, 0, $exp, 647 + $y).'</td>';
					print '</tr>';
				}
				
				print '</table>';
				
			} else if($function === "listRunes") {
				$modes = array("SOFTCORE", "HARDCORE");
				print '<div class="panel-heading"><h2 class="panel-title text-center">Runes ('.$modes[$argument].')</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet"> </th>';
				print '<th width="*" class="text-center exocet"><strong>EuroL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EuroNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaNL</strong></th>';
				print '</tr></thead>';
				
				$array = array("El", "Eld", "Tir", "Nef", "Eth", "Ith", "Tal", "Ral", "Ort", "Thul", "Amn", "Sol", "Shael", "Dol", "Hel", "Io", "Lum", "Ko", "Fal", "Lem", "Pul", "Um", "Mal", "Ist", "Gul", "Vex", "Ohm", "Lo", "Sur", "Ber", "Jah", "Cham", "Zod");
				for ($y = 0; $y < count($array); $y++) {
					print '<tr>';
					// realms: { "uswest": 0, "useast": 1, "asia": 2, "europe": 3 },
					// Runes($queryR, $queryHC, $queryLD, $queryEXP, $itemId)
					print '<td width="30%" class="text-left rune"><img src="images/items/r'.$array[$y].'.png"> '.$array[$y].'</td>';
					print '<td width="*" class="text-center">'.Runes(3, $argument, 1, $exp, 610 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(3, $argument, 0, $exp, 610 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(1, $argument, 1, $exp, 610 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(1, $argument, 0, $exp, 610 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(0, $argument, 1, $exp, 610 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(0, $argument, 0, $exp, 610 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(2, $argument, 1, $exp, 610 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(2, $argument, 0, $exp, 610 + $y).'</td>';
					print '</tr>';
				}
				
				print '</table>';
				
			} else if($function === "listGems") {
				$modes = array("SOFTCORE", "HARDCORE");
				print '<div class="panel-heading"><h2 class="panel-title text-center">Gems ('.$modes[$argument].')</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet"> </th>';
				print '<th width="*" class="text-center exocet"><strong>EuroL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EuroNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaNL</strong></th>';
				print '</tr></thead>';

				$array = array(
					"<img src=\"images/items/gsva.png\"> Amethyst",
                    "<img src=\"images/items/gsvb.png\"> Amethyst",
                    "<img src=\"images/items/gsvc.png\"> Amethyst",
                    "<img src=\"images/items/gsvd.png\"> Amethyst",
                    "<img src=\"images/items/gsve.png\"> Amethyst",

					"<img src=\"images/items/gsya.png\"> Topaz",
                    "<img src=\"images/items/gsyb.png\"> Topaz",
                    "<img src=\"images/items/gsyc.png\"> Topaz",
                    "<img src=\"images/items/gsyd.png\"> Topaz",
                    "<img src=\"images/items/gsye.png\"> Topaz",

					"<img src=\"images/items/gsba.png\"> Sapphire",
                    "<img src=\"images/items/gsbb.png\"> Sapphire",
                    "<img src=\"images/items/gsbc.png\"> Sapphire",
                    "<img src=\"images/items/gsbd.png\"> Sapphire",
                    "<img src=\"images/items/gsbe.png\"> Sapphire",

					"<img src=\"images/items/gsga.png\"> Emerald",
                    "<img src=\"images/items/gsgb.png\"> Emerald",
                    "<img src=\"images/items/gsgc.png\"> Emerald",
                    "<img src=\"images/items/gsgd.png\"> Emerald",
                    "<img src=\"images/items/gsge.png\"> Emerald",

					"<img src=\"images/items/gsra.png\"> Ruby",
                    "<img src=\"images/items/gsrb.png\"> Ruby",
                    "<img src=\"images/items/gsrc.png\"> Ruby",
                    "<img src=\"images/items/gsrd.png\"> Ruby",
                    "<img src=\"images/items/gsre.png\"> Ruby",

					"<img src=\"images/items/gswa.png\"> Diamond",
                    "<img src=\"images/items/gswb.png\"> Diamond",
                    "<img src=\"images/items/gswc.png\"> Diamond",
                    "<img src=\"images/items/gswd.png\"> Diamond",
                    "<img src=\"images/items/gswe.png\"> Diamond"
				);
				for ($y = 0; $y < count($array); $y = $y + 1) {
					print '<tr>';
					// realms: { "uswest": 0, "useast": 1, "asia": 2, "europe": 3 },
					// Runes($queryR, $queryHC, $queryLD, $queryEXP, $itemId)
					print '<td width="30%" class="text-left">'.$array[$y].'</td>';
					print '<td width="*" class="text-center">'.Runes(3, $argument, 1, $exp, 557 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(3, $argument, 0, $exp, 557 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(1, $argument, 1, $exp, 557 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(1, $argument, 0, $exp, 557 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(0, $argument, 1, $exp, 557 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(0, $argument, 0, $exp, 557 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(2, $argument, 1, $exp, 557 + $y).'</td>';
					print '<td width="*" class="text-center">'.Runes(2, $argument, 0, $exp, 557 + $y).'</td>';
					print '</tr>';
				}

				print '</table>';

			} else if($function === "listSS") {
				$modes = array("SOFTCORE", "HARDCORE");
				print '<div class="panel-heading"><h2 class="panel-title text-center">Perfect Skull + Stone of Jordan ('.$modes[$argument].')</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet"> </th>';
				print '<th width="*" class="text-center exocet"><strong>EuroL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EuroNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaNL</strong></th>';
				print '</tr></thead>';

				$array = array(
					"Perfect Skull",
                    "The Stone of Jordan Ring"
				);
				$imagino = array(
					"<img src=\"images/items/skz.png\">",
					"<img src=\"images/items/rin3.png\">"
				);
				for ($y = 0; $y < count($array); $y++) {
					print '<tr>';
					// realms: { "uswest": 0, "useast": 1, "asia": 2, "europe": 3 },
					// Runes($queryR, $queryHC, $queryLD, $queryEXP, $itemId)
					print '<td width="30%" class="text-left">'.$imagino[$y].' '.$array[$y].'</td>';
					print '<td width="*" class="text-center">'.ListByName(3, $argument, 1, $exp, $array[$y]).'</td>';
					print '<td width="*" class="text-center">'.ListByName(3, $argument, 0, $exp, $array[$y]).'</td>';
					print '<td width="*" class="text-center">'.ListByName(1, $argument, 1, $exp, $array[$y]).'</td>';
					print '<td width="*" class="text-center">'.ListByName(1, $argument, 0, $exp, $array[$y]).'</td>';
					print '<td width="*" class="text-center">'.ListByName(0, $argument, 1, $exp, $array[$y]).'</td>';
					print '<td width="*" class="text-center">'.ListByName(0, $argument, 0, $exp, $array[$y]).'</td>';
					print '<td width="*" class="text-center">'.ListByName(2, $argument, 1, $exp, $array[$y]).'</td>';
					print '<td width="*" class="text-center">'.ListByName(2, $argument, 0, $exp, $array[$y]).'</td>';
					print '</tr>';
				}

				print '</table>';

			} else if($function === "listSojs") {
				$modes = array("SOFTCORE", "HARDCORE");
				print '<div class="panel-heading"><h2 class="panel-title text-center">Stone of Jordan ('.$modes[$argument].')</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet"> </th>';
				print '<th width="*" class="text-center exocet"><strong>EuroL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EuroNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>EastNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>WestNL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaL</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>AsiaNL</strong></th>';
				print '</tr></thead>';

				$array = array(
                    "The Stone of Jordan Ring",
                    "The Stone of Jordan Ring",
                    "The Stone of Jordan Ring",
                    "The Stone of Jordan Ring",
                    "The Stone of Jordan Ring"
				);
				$imagino = array(
					"<img src=\"images/items/rin1.png\">",
					"<img src=\"images/items/rin2.png\">",
					"<img src=\"images/items/rin3.png\">",
					"<img src=\"images/items/rin4.png\">",
					"<img src=\"images/items/rin5.png\">",
				);
				for ($y = 0; $y < count($array); $y++) {
					print '<tr>';
					// realms: { "uswest": 0, "useast": 1, "asia": 2, "europe": 3 },
					// Runes($queryR, $queryHC, $queryLD, $queryEXP, $itemId)
					print '<td width="30%" class="text-left">'.$imagino[$y].' '.$array[$y].'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(3, $argument, 1, $exp, $array[$y], $y+1).'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(3, $argument, 0, $exp, $array[$y], $y+1).'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(1, $argument, 1, $exp, $array[$y], $y+1).'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(1, $argument, 0, $exp, $array[$y], $y+1).'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(0, $argument, 1, $exp, $array[$y], $y+1).'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(0, $argument, 0, $exp, $array[$y], $y+1).'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(2, $argument, 1, $exp, $array[$y], $y+1).'</td>';
					print '<td width="*" class="text-center">'.ListByNameSkin(2, $argument, 0, $exp, $array[$y], $y+1).'</td>';
					print '</tr>';
				}

				print '</table>';

			} else if($function === "showLogs") {
                print '<div class="panel-heading"><h2 class="panel-title text-center">Drop logs viewer</h2></div>';
                print '<div id="textoutput">';
                Logs();
                print '</div>';

            } else if($function === "ShowFile") {
                print '<div class="panel-heading"><h2 class="panel-title text-center">Drop logs viewer ('.$argument.')</h2></div>';
                print '<div id="textoutput">';
                print '<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Show only 100 last lines in reverse order!</div>';
                ShowFile($argument);
                print '</div>';

            } else if($function === "listSales") {
                print '<div class="panel-heading"><h2 class="panel-title text-center">Sales Statistics</h2></div>';
				print '<table class="table table-hover diablo">';
				print '<thead><tr>';
				print '<th width="30%" class="text-left exocet">User</th>';
				print '<th width="*" class="text-center exocet"><strong>FG Ammount</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>Drops Requested</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>Drops Success</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>Success %</strong></th>';
				print '<th width="*" class="text-center exocet"><strong>Fg/Item</strong></th>';
				print '</tr></thead>';
				SalesStats();
				print '</table>';
            } else {
				print '<div class="panel-heading"><h2 class="panel-title text-center">Oops! Something is not ready yet :(</h2></div>';
				print '<div id="textoutput">';
					print '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> You have been logged as hacker! Your IP is <strong>'.$_SERVER["REMOTE_ADDR"].'</strong> what are you trying to do ?</div>';
				print '</div>';
			}
			?>
				<script>
					$(".confirm").click(function(e){
						e.preventDefault();
						var fun = $(this).attr('function');
						var arg = $(this).attr('arg');
						$.ajax({ 
							type: 'POST',
							url: "sql.php",
							data: {
								fun: fun,
								arg: arg
							},
							beforeSend: function(){
								$('.loader').show()
							},
							success: function(data) { 
								$("#output").html(data);
								$('.loader').hide();					
							} 
						}); 
					});
				</script>
			<?php
		} else {
			die(header("HTTP/1.1 401 Unauthorized"));
			//die("trying to find something interesting ?");
		}
	} else {
		die(header("HTTP/1.1 401 Unauthorized"));
		//die("trying to find something interesting ?");
	}
	
	function DeleteEquip() {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			$sql = 'DELETE FROM muleItems WHERE itemLocation = 1';
			$conn->query($sql);
			$conn = NULL;
			return true;

		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
			return false;
		}
	}
	function Convert() {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");
			$sql = 'UPDATE muleChars SET charLadder = 0';
			$conn->query($sql);
			$conn = NULL;
			return true;
		} catch(PDOException $e) {
			$conn = NULL;
			print 'Exception : '.$e->getMessage();
			return false;
		}
	}
	
	function Runes($queryR, $queryHC, $queryLD, $queryEXP, $itemId) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB	= " AND charExpansion = ".$queryEXP;
			$tempC	= " AND charLadder = ".$queryLD;
			$tempD	= " AND itemClassid = ".$itemId;
			
			$sql = /** @lang text */
				'SELECT COUNT() AS "count" FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountId WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'';
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

	function ListByName($queryR, $queryHC, $queryLD, $queryEXP, $itemId) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB	= " AND charExpansion = ".$queryEXP;
			$tempC	= " AND charLadder = ".$queryLD;
			$tempD	= " AND itemName = '".$itemId."'";

			$sql = /** @lang text */
				'SELECT COUNT() AS "count" FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountId WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'';
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

	function ListByNameSkin($queryR, $queryHC, $queryLD, $queryEXP, $itemId, $skin) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB	= " AND charExpansion = ".$queryEXP;
			$tempC	= " AND charLadder = ".$queryLD;
			$tempD	= " AND itemName = '".$itemId."'";
			$tempE	= " AND itemImage = 'rin".$skin."'";

			$sql = /** @lang text */
				'SELECT COUNT() AS "count" FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountId WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.' '.$tempE.'';
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
	
	function Torch($queryR, $queryHC, $queryLD, $queryEXP, $itemId) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB	= " AND charExpansion = ".$queryEXP;
			$tempC	= " AND charLadder = ".$queryLD;
			$tempD	= " AND itemClassid = ".$itemId;
			
			$sql = /** @lang text */
				'SELECT COUNT() AS "count" FROM muleItems LEFT JOIN muleChars ON itemCharId = charId LEFT JOIN muleAccounts ON charAccountId = accountId WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'';
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
	
	function Anni($queryR, $queryHC, $queryLD, $queryEXP, $mode) {
		try {
			$conn = new PDO('sqlite:ItemDB.s3db') or die("Unable to connect");

			$tempA	= " AND charHardcore = ".$queryHC;
			$tempB	= " AND charExpansion = ".$queryEXP;
			$tempC	= " AND charLadder = ".$queryLD;
			$tempD	= " AND itemQuality = 7 AND itemClassid = 603";
			$sql	= "";
			
			// Perfect OMG WTF!
			if($mode === 0) {
				$sqlA = /** @lang text */
					'SELECT DISTINCT itemId
							FROM muleItems 
								LEFT JOIN muleChars ON itemCharId = charId 
								LEFT JOIN muleAccounts ON charAccountId = accountId 
								LEFT JOIN muleItemsStats ON statsItemId = itemId 
							WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.' 
								AND statsName = "strength" 
								AND statsValue = 20';
								
				$results = $conn->query($sqlA);
				$itemsDB = $results->fetchAll(PDO::FETCH_ASSOC);
				$string = array();
				foreach ($itemsDB as $nr => $item) {
					array_push($string, ' statsItemId = '.$item["itemId"].' ');
				}
				if (count($string) === 0) {
					return 0;
				}
				$glue = implode("OR", $string);
				$sqlB = /** @lang text */
					'SELECT DISTINCT statsItemId
							FROM muleItemsStats 
							WHERE ('.$glue.') 
								AND statsName = "fireresist" 
								AND statsValue = 20';		
								
				$results = $conn->query($sqlB);
				$itemsDB = $results->fetchAll(PDO::FETCH_ASSOC);
				$string = array();
				foreach ($itemsDB as $nr => $item) {
					array_push($string, ' statsItemId = '.$item["itemId"].' ');
				}
				if (count($string) === 0) {
					return 0;
				}
				$glue = implode("OR", $string);
				$sql = /** @lang text */
					'SELECT COUNT(DISTINCT statsItemId) as "count"
							FROM muleItemsStats 
							WHERE ('.$glue.') 
								AND statsName = "itemaddexperience" 
								AND statsValue = 10';
			}
			// +20 To All Attributes
			if($mode === 1) {
				$sql = /** @lang text */
					'SELECT COUNT(DISTINCT itemId) AS "count"
							FROM muleItems 
								LEFT JOIN muleChars ON itemCharId = charId 
								LEFT JOIN muleAccounts ON charAccountId = accountId 
								LEFT JOIN muleItemsStats ON statsItemId = itemId 
							WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'  AND statsName = "strength" AND statsValue = 20';
			}
			// All Resistances +20
			if($mode === 2) {
				$sql = /** @lang text */
					'SELECT COUNT(DISTINCT itemId) AS "count"
							FROM muleItems 
								LEFT JOIN muleChars ON itemCharId = charId 
								LEFT JOIN muleAccounts ON charAccountId = accountId 
								LEFT JOIN muleItemsStats ON statsItemId = itemId 
							WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'  AND statsName = "fireresist" AND statsValue = 20';
			}
			// 10% To Experience Gained
			if($mode === 3) {
				$sql = /** @lang text */
					'SELECT COUNT(DISTINCT itemId) AS "count"
							FROM muleItems 
								LEFT JOIN muleChars ON itemCharId = charId 
								LEFT JOIN muleAccounts ON charAccountId = accountId 
								LEFT JOIN muleItemsStats ON statsItemId = itemId 
							WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.' AND statsName = "itemaddexperience" AND statsValue = 10';
			}
			// Unidentified
			if($mode === 4) {
				$sql = /** @lang text */
					'SELECT COUNT(DISTINCT itemId) AS "count"
							FROM muleItems 
								LEFT JOIN muleChars ON itemCharId = charId 
								LEFT JOIN muleAccounts ON charAccountId = accountId 
								LEFT JOIN muleItemsStats ON statsItemId = itemId 
							WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.' AND lower(itemDescription) LIKE "%unidentified%"';
			}
			// All
			if($mode === 5) {
				$sql = /** @lang text */
					'SELECT COUNT(DISTINCT itemId) AS "count"
							FROM muleItems 
								LEFT JOIN muleChars ON itemCharId = charId 
								LEFT JOIN muleAccounts ON charAccountId = accountId 
								LEFT JOIN muleItemsStats ON statsItemId = itemId 
							WHERE accountRealm = '.$queryR.' '.$tempA.' '.$tempB.' '.$tempC.' '.$tempD.'';				
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

	function Logs() {
        function newest($a, $b)
        {
            return filemtime($a) - filemtime($b);
        }
		date_default_timezone_set('Europe/London');
        $dir = glob('logs/*'); // put all files in an array
        uasort($dir, "newest"); // sort the array by calling newest()
        print '<div class="confirm" function="ShowFile" arg="ItemDB.log" class="confirm">ItemDB.log<span class="pull-right">'.date ("F d Y H:i:s.", filemtime("ItemDB.log")).'</span></div>';
        foreach($dir as $file)
        {
			if(basename($file) !== "fg") {
				print '<div class="confirm" function="ShowFile" arg="logs/'.basename($file).'" class="confirm">'.basename($file).'<span class="pull-right">'.date ("F d Y H:i:s.", filemtime('logs/'.basename($file))).'</span></div><br />';
			}
        }
    }

    function ShowFile($filename)
    {
        /* Read file from end line by line */
        $fp = fopen( dirname(__FILE__) . "\\". $filename, 'r');
        $lines_read = 0;
        $lines_to_read = 100;
        fseek($fp, 0, SEEK_END); //goto EOF
        $eol_size = 2; // for windows is 2, rest is 1
        $eol_char = "\r\n"; // mac=\r, unix=\n
        while ($lines_read < $lines_to_read) {
            if (ftell($fp)==0) break; //break on BOF (beginning...)
            do {
                fseek($fp, -1, SEEK_CUR); //seek 1 by 1 char from EOF
                $eol = fgetc($fp) . fgetc($fp); //search for EOL (remove 1 fgetc if needed)
                fseek($fp, -$eol_size, SEEK_CUR); //go back for EOL
            } while ($eol != $eol_char && ftell($fp)>0 ); //check EOL and BOF

            $position = ftell($fp); //save current position
            if ($position != 0) fseek($fp, $eol_size, SEEK_CUR); //move for EOL
            echo fgets($fp)."<br>"; //read LINE or do whatever is needed
            fseek($fp, $position, SEEK_SET); //set current position
            $lines_read++;
        }
        fclose($fp);/* Read file from end line by line */
    }
	
	function AddUser($login, $pass) {
		$hash = base64_encode(sha1($pass, true));
		$contents = $login . ':{SHA}' . $hash;
		file_put_contents('.htpasswd', $contents."\n", FILE_APPEND);
	}

	function SalesStats () {
		$dir = glob('logs/*');
		foreach ($dir as $file) {
			UserSalesStats($file);
		}
	}
	
	function UserSalesStats ($filename) {
		// Stats for a user
		$fg = 0; // FG total
		$dropa = 0; // Drop Attempts
		$drops = 0; // Drop Successes

		$fp = @fopen($filename, "rt");
		if ($fp) {
			while (($line = fgets($fp)) !== false) {
				$parts = explode(" ", $line);
				// Line with the following format
				// [2012.12.12 12:12:12] <dropper1> Trying to drop 1 items. VALUE: 1
				if (count($parts) == 10) {
					$dropa += $parts[6];
					$fg += $parts[9];
				}
				// Otherwise the line should be in this format when it actually dropped something
				// [2012.12.12 12:12:12] <dropper1> [ profile: "dropper1" dropped: "El Rune" game: "game//pass" value: 1]
				else if (count($parts) > 10) {
					$drops += 1;
				}
			}

			echo '<tr>';
			echo '<td>' . substr(basename($filename), 5, -4) . '</td>';
			echo '<td class="text-center">' . $fg . '</td>';
			echo '<td class="text-center">' . $dropa . '</td>';
			echo '<td class="text-center">' . $drops . '</td>';
			echo '<td class="text-center">' . sprintf('%.3f', strval((($drops / $dropa) * 100))) . '</td>';
			echo '<td class="text-center">' . sprintf('%.3f', strval($fg / $drops)) . '</td>';
			echo '</tr>';
		}
		fclose($fp);
	}
?>
