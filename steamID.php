<?php include('inc-header.php') ?>

<?php if(isset($_POST["steamid"])){
	$id = $_POST["steamid"];
	$steamwidget = new SteamWidget();
	$steamid64 = $steamwidget->get64Id($id);
} ?>
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

<?php include('inc-footer.php') ?>