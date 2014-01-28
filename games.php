<?php include('inc-header.php') ?>

<div class="col-md-12">
	<h1>Preformatted Games List</h1>
	<p>For shorter inline code the preformated table is a very nice way to just have the table appear already set up and ready to go. You can change the table layout in <code>SteamWidget.class.php</code> under the <code>query_games_styled()</code> function. The code to display this table is as follows.</p>
<pre>
&lt;?php $steamwidget = new SteamWidget();
echo $steamwidget->query_games_styled($steamID64ToQuery); ?&gt;</pre>
	<br />

</div>
<div class="col-md-12">

	<?php $steamwidget = new SteamWidget();
	echo $steamwidget->query_games_styled(); ?>

</div>

<?php include('inc-footer.php') ?>