<?php include('inc-header.php') ?>

<div class="col-md-12">
	<h1>Steam Status</h1>
	<p>Call a user's current online status and display name plus any game they might be playing at that time.</p>

<pre>
&lt;?php $steamwidget = new SteamWidget();
	echo $steamwidget->current_steam_status($UserIdToQuery); ?&gt;
</pre>
<br />
</div>
<div class="col-md-12">
		<h2><?php $steamwidget = new SteamWidget();
		echo $steamwidget->current_steam_status(); ?>
		</h2>
</div>

<?php include('inc-footer.php') ?>