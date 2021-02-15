<!DOCTYPE html>
<html lang="en">

<!-- Include Functions -->
<?php require 'functions.php';
	if ($_SERVER['PHP_AUTH_USER'] !== $admin or !isset($_SERVER["HTTP_REFERER"])) {
		die(header ("Location: /index.php"));
	}
	$themeName = getTheme($_SERVER['PHP_AUTH_USER']);
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Item Manager - Admin page">
    <meta name="author" content="dzik">
	<link rel="shortcut icon" href="images/icons/Akara.ico">
    <title>dzik's Item Manager - Admin page</title>

    <!-- Bootstrap Core CSS -->
    <link id="layout1" rel="stylesheet" href="themes/<?php echo $themeName; ?>/css/bootstrap.css">

    <!-- Custom CSS -->
	<link id="layout2" rel="stylesheet" type="text/css" href="themes/<?php echo $themeName; ?>/css/itemManager.css">
	<link id="layout3" rel="stylesheet" type="text/css" href="themes/<?php echo $themeName; ?>/css/tooltipster.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php showThemes();?>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<?php 
					if (file_exists("2602b25173cc02538edd71464acd4fba.php")) {
						print '<li><a class="exocet" href="2602b25173cc02538edd71464acd4fba.php">SQLite Admin<span class="caret"></span></a></li>';
					}
					print '<li><a class="exocet" href="'.$_SERVER["HTTP_REFERER"].'">BACK TO MAIN PAGE <span class="caret"></span></a></li>'; ?>
				</ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
		<!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
			<div class="col-md-12 form-group">
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title">ADMIN FUNCTION</h1>
						</div>
						<ul class="list-group">
							<li class="list-group-item">
								<a function="showLogs" arg="" exp="" class="mainmenu">DROP LOGS</a>
							</li>
							<li class="list-group-item">
								<a function="finishLadder" arg="" exp="" class="mainmenu">FINISH LADDER</a>
							</li>
							<!-- 
							<li class="list-group-item">
								<a function="deleteAccounts" arg="" class="mainmenu">Delete Accounts</a>
							</li>
							 -->
							<li class="list-group-item">
								<a function="deleteEquipped" arg="" exp="" class="mainmenu">Delete Equiped</a>
							</li>
							<li class="list-group-item">
								<a function="listSales" arg="" exp="" class="mainmenu">Sales Statistics</a>
							</li>
						</ul>
						<div class="panel-heading">
							<h1 class="panel-title">ITEMS LISTS (EXPANSION)</h1>
						</div>
						<ul class="list-group">
							<!--
							<li class="list-group-item">
								<a function="listTorch" arg="0" exp="1" class="mainmenu">Hellfire Torch (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listTorch" arg="1" exp="1" class="mainmenu">Hellfire Torch (HC)</a>
							</li>
							 -->
							<li class="list-group-item">
								<a function="listAnni" arg="0" exp="1" class="mainmenu">Annihilus (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listAnni" arg="1" exp="1" class="mainmenu">Annihilus (HC)</a>
							</li>
							<li class="list-group-item">
								<a function="listRunes" arg="0" exp="1" class="mainmenu">Runes (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listRunes" arg="1" exp="1" class="mainmenu">Runes (HC)</a>
							</li>
							<li class="list-group-item">
								<a function="listGems" arg="0" exp="1" class="mainmenu">Gems (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listGems" arg="1" exp="1" class="mainmenu">Gems (HC)</a>
							</li>
							<li class="list-group-item">
								<a function="listSojs" arg="0" exp="1" class="mainmenu">The Stone of Jordan (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listSojs" arg="1" exp="1" class="mainmenu">The Stone of Jordan (HC)</a>
							</li>
							<li class="list-group-item">
								<a function="listPandemonium" arg="0" exp="1" class="mainmenu">Pandemonium Event (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listPandemonium" arg="1" exp="1" class="mainmenu">Pandemonium Event (HC)</a>
							</li>
						</ul>
						<div class="panel-heading">
							<h1 class="panel-title">ITEMS LISTS (CLASSIC)</h1>
						</div>
						<ul class="list-group">
							<li class="list-group-item">
								<a function="listSS" arg="0" exp="0" class="mainmenu">SKULL+SOJ (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listSS" arg="1" exp="0" class="mainmenu">SKULL+SOJ (HC)</a>
							</li>
							<li class="list-group-item">
								<a function="listGems" arg="0" exp="0" class="mainmenu">Gems (SC)</a>
							</li>
							<li class="list-group-item">
								<a function="listGems" arg="1" exp="0" class="mainmenu">Gems (HC)</a>
							</li>
						</ul>
						<div class="panel-heading">
							<h1 class="panel-title">Repository</h1>
						</div>
						<ul class="list-group">
							<li class="list-group-item text-center">
								<br>
								<a href="https://github.com/dzik87/D2Dropper" target="_blank"><img src="https://img.shields.io/github/last-commit/dzik87/D2Dropper?style=for-the-badge"></a>
								<br><br>
								<a href="https://discordapp.com/users/209047731996590080" target="_blank"><img src="https://img.shields.io/badge/Discord-dzik%232463-orange.svg?style=for-the-badge&logo=discord"></a>
								<br><br>
								<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KWYLPLQXAAQKS" target="_blank"><img src="https://img.shields.io/badge/PayPal-Donate-blue.svg?style=for-the-badge&logo=paypal"></a>
								<br><br>
							</li>
						</ul>
						<div class="panel-heading">
							<h1 class="panel-title">Contributors</h1>
						</div>
						<ul class="list-group"id="contributors">
						</ul>
					</div>
				</div>
						
				<div class="col-md-9">
					<div class="panel panel-default" id="output">
						<div class="panel-heading">
							<h1 class="panel-title">ADMIN PANEL</h1>
						</div>
						
					</div>
				</div>
			</div>
        </div>
        <!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default text-center">
					<div class="panel-footer">
						ItemManager 2021 &copy; dzik
					</div>
				</div>
			</div>
		</div>
        <!-- /.row -->
		
    </div>
    <!-- /.container -->
	
	<div class="loader">
	   <div style="text-align: center;">
		   <img class="loading-image" src="images/ajax-loader.gif" alt="loading..">
	   </div>
	</div>
	
	<div class="progress upload-progress">
	  <div class="progress-bar progress-bar-warning progress-bar-striped active uploading-image" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
		uploading...
	  </div>
	</div>

	
    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>
	
	<!-- Admin Panel JS -->
	<script>
		$("a.mainmenu").click(function(e){ 
			e.preventDefault();
			var fun = $(this).attr('function');
			var arg = $(this).attr('arg');
			var exp = $(this).attr('exp');
			$.ajax({ 
				type: 'POST',
				url: "sql.php",
				data: {
					fun: fun,
					arg: arg,
					exp: exp
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
		$(document).bind("contextmenu",function(e){
			e.preventDefault();		
			return false;
		});
		// Handle theme loading and saving.
		$("a.themeselect").click(function(e){ 
			e.preventDefault();
			
			var theme = $(this).attr('theme');
            $.ajax({
                type: 'POST',
                url: 'theme.php',
                data: {theme: theme},
                beforeSend: function(){
                    $('.loader').show()
                },
                success:  function(data){
					$("head link#layout1").attr("href", "themes/" + theme + "/css/bootstrap.css");
					$("head link#layout2").attr("href", "themes/" + theme + "/css/itemManager.css");
					$("head link#layout3").attr("href", "themes/" + theme + "/css/tooltipster.css");
                    $('.loader').hide();
                }
            });
		});
		
		function GetContributors() {
			$.ajax({
				type:'GET',
				url: 'https://api.github.com/repos/dzik87/D2Dropper/contributors',
				dataType: 'JSON',
				success: function(data) {
					let str = ""
					for (u in data) {
						str += '<li class="list-group-item"><a href="' + data[u].html_url + '" target="_blank" class="mainmenu"><img src="' + data[u].avatar_url + '" width="40">&nbsp;&nbsp;&nbsp;' + data[u].login + '&nbsp;&nbsp;(' + data[u].contributions + ')</a></li>';
					}
					$("#contributors").html(str);
				},

				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
				}
			});
		}
		GetContributors();
	</script>

    <!-- Tooltipster 3.3.0 -->
    <script type="text/javascript" src="js/jquery.tooltipster.js"></script>

</body>

</html>
