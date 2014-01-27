<?php
//error_reporting(E_ALL); // for debugging.
require 'SteamWidget.class.php';

if(isset($_POST["steamid"])){
	$id = $_POST["steamid"];
	$steamwidget = new SteamWidget();
	$steamid64 = $steamwidget->get64Id($id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Steam Widget API">
	<meta name="version" content="2.0">
    <meta name="author" content="TonyBilby">
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

</head>
<body>
	<div class="container" id="wrap">
		<div class="navbar navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>-->
					<a class="navbar-brand" href="#">Steam Widget</a>
				</div>
				<div class="collapse navbar-collapse">
					<!--<ul class="nav navbar-nav">
						<li class="active"><a href="#">Home</a></li>
					</ul>-->
				</div>
			</div>
		</div>

		<div class="container">
			<?php if(isset($steamid64)): ?>
			<br />
			<div class="col-md-12">
				<div class="alert alert-success"><?php echo 'SteamID64 for <b>'.$_POST["steamid"].'</b> is <b>'.$steamid64.'</b>'; ?></div>
			</div>
			<?php endif; ?>
			<div class="col-md-12"><br />
				<form action="" method="post" role="form">
					<div class="input-group input-group-lg">
						<span class="input-group-addon">SteamID to convert</span>
						<input type="text" class="form-control" placeholder="Steam ID" name="steamid">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit">Convert</button>
						</span>
					</div>
				</form><br />
			</div>
			<div class="col-md-12">
				<h2>
					<?php $steamwidget = new SteamWidget();
					echo $steamwidget->current_steam_status(); ?>
				</h2>
				<?php echo $steamwidget->tb_download_database(); ?>
			</div>
		</div>
	</div>
	<div id="footer">
		<div class="container">
			<p class="text-muted">
				<a rel="license" href="http://creativecommons.org/licenses/by/4.0/deed.en_US">
					<img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/4.0/80x15.png" />
				</a>
				<span xmlns:dct="http://purl.org/dc/terms/" property="dct:title"><a xmlns:dct="http://purl.org/dc/terms/" href="https://github.com/tonybilby/SteamWidget" rel="dct:source" title="Based on work here.">Steam Widget</a></span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName">Tony Bilby</span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/4.0/deed.en_US" title="Attribution 4.0 International License">Creative Commons</a>.
			</p>
		</div>
    </div>
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>
</html>
