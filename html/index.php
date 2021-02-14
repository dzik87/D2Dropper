<!DOCTYPE html>
<html lang="en">

<!-- Include Functions -->
<?php 
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$start = $time;
	require 'functions.php';
	require 'config.php';
	$themeName = getTheme($_SERVER['PHP_AUTH_USER']);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Item Manager">
    <meta name="author" content="dzik">
	<link rel="shortcut icon" href="images/icons/Akara.ico">
    <title>dzik's Item Manager</title>

    <!-- Bootstrap Core CSS -->
    <link id="layout1" rel="stylesheet" href="themes/<?php echo $themeName; ?>/css/bootstrap.css">

    <!-- Custom CSS -->
	<link id="layout2" rel="stylesheet" type="text/css" href="themes/<?php echo $themeName; ?>/css/itemManager.css">
	<link id="layout3" rel="stylesheet" type="text/css" href="themes/<?php echo $themeName; ?>/css/tooltipster.css">
    <link id="layout4" rel="stylesheet" type="text/css" href="themes/<?php echo $themeName; ?>/css/jquery-ui.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.min.js"></script>
</head>
<body>
    <div class="modal fade" id="changelogMod" tabindex="-1" role="dialog" aria-labelledby="changelogMod" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title color4" id="changelogLabel">Changelog</h4>
                </div>
                <div class="modal-body" id="changes">
                </div>
            </div>
        </div>
    </div>
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
                <ul class="nav navbar-nav">
                    <?php buildMenu(); ?>
                </ul>
				<ul class="nav navbar-nav navbar-right">
					<?php userAccess(); ?>
				</ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
		<!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
			<?php
			if(!isset($showAccounts)) {
				$showAccounts = true;
			}
			if ($showAccounts == true) {?>
				<div class="col-md-12 form-group">
					<?php 
						if(getRealmCount()) {
							//	accounts
							print '<div class="col-md-3">';
								print '<div class="panel panel-default">';
									print '<div class="panel-heading">';
										print '<h1 class="panel-title">ACCOUNTS LIST</h1>';
									print '</div>';
									getAccounts();
								print '</div>';
							print '</div>';
							
							// items
							print '<div class="col-md-9">';
								print '<div class="panel panel-default" id="itemsoutput">';
									print '&nbsp;';
								print '</div>';
							print '</div>';
						}
					?>
				</div>
			<?php 
			} else {
				// dont show accounts.
				if(getRealmCount()) {
					print '<div class="panel panel-default" id="itemsoutput">';
						print '<div class="panel-heading">';
							print '<h1 class="panel-title">SEARCH FOR ITEMS</h1>';
						print '</div>';
						?>
							<span class='top' style="margin-left:7px">Please find your items<br></span>
							<span class='top' style="margin-left:7px">Searching will output items with words in items description<br></span>
							<span class='top' style="margin-left:7px" >For example to find Stone of Jordan Ring you can type <STRONG>stone,jordan,ring<STRONG><br></span>
							<span class='top' style="margin-left:7px">You can specify more than one word if you separate them with a '<STRONG>,</STRONG>'<br></span>
						<?php
					print '</div>';
				}				
			}
			?>
        </div>
		
        <!-- /.row -->
		<br><br><br><br><br><br>
		
		<div class="row">
			<div class="panel panel-default text-center">
				<div class="panel-footer">
					ItemManager 2021 &copy; dzik
					<?php
						$time = microtime();
						$time = explode(' ', $time);
						$time = $time[1] + $time[0];
						$finish = $time;
						$total_time = round(($finish - $start), 4);
						echo '<br />Page loaded in '.$total_time.' seconds.';
					?>
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

	<script>var user = "<?php print $currUser; ?>";</script>

    <!-- jQuery Version 1.11.1
    <script src="js/jquery.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	-->
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>
	
	<!-- Tooltipster 3.3.0 -->
    <script type="text/javascript" src="js/jquery.tooltipster.js"></script>
	
	<!-- Item Manager Functions -->
	<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="js/html2canvas.js"></script>
    <script type="text/javascript" src="js/itemManager.js"></script>
    <script type="text/javascript" src="js/itemManagerShow.js"></script>

	<script>
		//variable with names to display.
		var show 	= [];
		var rowsid	= [];
		var hideid	= [];
		var droparray = [];
		$('#<?php print $showthat; ?>').collapse('show');
	</script>

</body>

</html>
