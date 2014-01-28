<?php include('inc-header.php') ?>

<div class="col-md-12">
	<h1>Unformatted Games List</h1>
	<p>For a more finite control over how you want the game list to be laid out use the <code>query_games()</code> function. This outputs a multidimensional array with the output of one game per second dimention of the array. you can easily echo each varible of the game using a foreach loop as shown below.</p>

<pre>
&lt;table class=&quot;table table-striped table-bordered&quot;&gt;
	&lt;tr&gt;&lt;th&gt;#&lt;/th&gt;&lt;th&gt;Logo&lt;/th&gt;&lt;th&gt;Games I Own&lt;/th&gt;&lt;th&gt;Playtime&lt;/th&gt;&lt;/tr&gt;
	
	&lt;?php $steamwidget = new SteamWidget();
	$games = $steamwidget-&gt;query_games($steamID64ToQuery);
	foreach ($games as $number =&gt; $game): ?&gt;
		&lt;tr&gt;
			&lt;td&gt;&lt;?php echo $game['number'];?&gt;&lt;/td&gt;
			&lt;td&gt;&lt;img src=&quot;&lt;?php echo $game['gamelogourl'];?&gt;&quot; /&gt;&lt;/td&gt;
			&lt;td&gt;&lt;h4&gt;&lt;a href=&quot;&lt;?php echo 'http://store.steampowered.com/app/' . $game['appid'];?&gt;&quot;&gt;&lt;?php echo $game['name'];?&gt;&lt;/a&gt;&lt;/h4&gt;&lt;/td&gt;
			&lt;td&gt;&lt;b&gt;&lt;?php echo $game['playtimeago'];?&gt;&lt;/b&gt;&lt;/td&gt;
		&lt;/tr&gt;
	&lt;?php endforeach; ?&gt;
&lt;/table&gt;</pre>
	<br />

</div>
<div class="col-md-12">
 
	<table class="table table-striped table-bordered">
		<tr>
			<th>#</th>
			<th>Logo</th>
			<th>Games I Own</th>
			<th>Playtime</th> 
		</tr>
		<?php $steamwidget = new SteamWidget();
		$games = $steamwidget->query_games(); //ARRAY (number, name, appid, playtimeago, gamelogourl)
		//print_r($games); //debugging
		foreach ($games as $number => $game): ?>
			<tr>
				<td style="text-align:center"><?php echo $game['number'];?></td>
				<td style="padding:0px;width:184px;"><img src="<?php echo $game['gamelogourl'];?>" height="69" width="184" /></td>
				<td><h4><a href="<?php echo 'http://store.steampowered.com/app/' . $game['appid'];?>"><?php echo $game['name'];?></a></h4></td>
				<td><b><?php echo $game['playtimeago'];?></b></td>
			</tr>
		<?php endforeach; ?>
	</table>
		
	

</div>

<?php include('inc-footer.php') ?>