<?php
	function setTheme($user, $theme) {
		$valid = getThemes();
		$confirm = FALSE;
		for($i = 0; $i < count($valid); $i++) {
			$a = $valid[$i];
			$pos = strpos($a, $theme);
			if($pos !== false) {
				$confirm = TRUE;
			}
		}
		if($confirm === TRUE) {
			$filename = "users/_THEME_$user.ini";
			$myfile = fopen($filename, "w+");
			fwrite($myfile, $theme);
			fclose($myfile);
			echo "Set theme to: $theme";
		} else {
			echo "such theme do not exists.";
		}
	}
	
	function getThemes() {
		$themes = array("default");
		foreach(glob('themes/*', GLOB_ONLYDIR) as $dir) {
			$dirName = basename($dir);
			if($dirName !== "default" and $dirName !== "images") {
				array_push($themes, $dirName);
			}
		}
		return $themes;
	}
	
	function getTheme($user) {
		$filename = "users/_THEME_$user.ini";
		if (file_exists($filename) === false) {
			$myfile = fopen($filename, "w+");
			fwrite($myfile, "default");
			fclose($myfile);
		}
		return file_get_contents($filename);
	}
	
	function showThemes() {
		$themes = getThemes();
		if(count($themes) > 1) {
			$username = $_SERVER['PHP_AUTH_USER'];
			//print '<li>';
			print '<a class="dropdown-toggle navbar-brand" id="themes" data-toggle="dropdown" href="#">Welcome '.$username.' <b class="caret"></b></a>';
			print '<ul class="dropdown-menu" role="menu" aria-labelledby="themes" style="left:35px;">';
				print '<li role="presentation" class="disabled"><a role="menuitem" tabindex="-1">Available themes</a></li>';
				for($i = 0; $i < count($themes); $i++) {
					$them = $themes[$i];
					print "<li role='presentation'><a role='themeitem' tabindex='-1' class='themeselect' theme='$them'>$them</a></li>";
				}
			print '</ul>';
			//print '</li>';
		} else {
			$username = $_SERVER['PHP_AUTH_USER'];
			print "<a class='navbar-brand'> Welcome $username </a>";
		}
	}
	
	if(isset($_SERVER['PHP_AUTH_USER']) and isset($_POST['theme'])) {
		$user = $_SERVER['PHP_AUTH_USER'];
		$theme = $_POST['theme'];
		setTheme($user, $theme);
	}
