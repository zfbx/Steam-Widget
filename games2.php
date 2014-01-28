<?php include('inc-header.php') ?>

<div class="col-md-12">
	Pre styled gamelist table output.
	<pre>
	&lt;?php $steamwidget = new SteamWidget();
	echo $steamwidget->query_games_styled(); ?&gt;</pre>
	<br />

</div>
<div class="col-md-12">

	<?php $steamwidget = new SteamWidget();
	echo $steamwidget->query_games_styled(); ?>

</div>

<?php include('inc-footer.php') ?>