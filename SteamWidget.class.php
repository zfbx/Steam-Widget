<?php

//------------------------------------------------
//		STEAM WIDGET CONFIGURATION 
//------------------------------------------------
define("APIKEY", "###########################");// Aquire at http://steamcommunity.com/dev/apikey
define("DEFAULTPROF", "76561198016593929"); 		//default profile to use, currently set to the developers.
define("NOIMAGE", "css/noimage.jpg");				// fallback game logo image for games without a logo
//define("CACHE", true); 							//cahce content for ## minute
//define("CACHETIME", 300); 						//time between updates (unix timestamp @ 5min)

//------------------------------------------------
//		STEAM WIDGET CLASS
//------------------------------------------------
class SteamWidget{

	// Converts userID OR user profile url to SteamID64
	public function get64Id($userID = null){
		if($userID == null){
			return DEFAULTPROF; //if nothing submitted, use the creators steam ID
		} else {
			if(strpos($userID, '/') != false) {
				$userID = preg_replace('#^https?://#', '', $userID);
				$userID = explode("/", $userID);
				$userID = $userID[2];
			}
			$sXML = simplexml_load_file('http://steamcommunity.com/id/'.$userID.'/?xml=1');
			$sID64 = $sXML->sID64;
			if ($sID64 != ''){
				return $sID64;
			} else {
				return false;
			}
		}
	}
	
	//Check for ID or url and convert or passthrough ID64
	public function checkFor64Id($userID = null){
		if($userID != null){
			if (strlen($userID) == 17 && is_numeric($userID)){
				return $userID;
			} else {
				$userID = $this->get64Id($userID);
				return $userID;
			}
		} else {return false;}
	}
	
	//return styled output of converting a userID to a SteamID64 
	public function get64IdCovnert($userID = null){
		$sID64 = $this->get64Id($userID);
		if ($sID64 != ''){
			return '<div class="alert alert-success"> SteamID64 for <b>'.$userID.'</b> is <b>'.$sID64.'</b></div>';
		} else {
			return '<div class="alert alert-danger"> There is no SteamID64 for <b>'.$userID.'</b></div>';
		}
	}
	
	//Last updated funtion (send it ago(timestamp) *use time() to get current UNIX timestamp
	public function ago($time){
		$m = time()-$time; $o='just now';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
		}return $o;
	}

	public function current_steam_status($sID64 = DEFAULTPROF) {
		$sID64 = $this->checkFor64Id($sID64); //TODO Catch NULL/FALSE
		$sXML = simplexml_load_file('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . APIKEY . '&format=xml&steamids='.$sID64);
		$sName = $sXML->players->player->personaname;
		if ($sName != "") {
			$sID = $sXML->players->player->steamid;
			$memberSince = $this->ago($sXML->players->player->timecreated);
			$profileURL = $sXML->players->player->userID;
			$hoursPlayed = $sXML->hoursPlayed2Wk;
			$sState = $sXML->players->player->personastate;
			$extraInfo = $sXML->players->player->gameextrainfo;
			$gameID = $sXML->players->player->gameid;
			$sColor2 = '#ffffff';
			$status2 = '';
			
			if ($sState == '1' && $gameID == ''){
				$status = 'Online<h2></h2>';
				$sColor = '#6186ad';
			} elseif ($sState == '1' && $gameID != ''){
				$gameURL = 'http://store.steampowered.com/app/' . $gameID;
				$status = 'Online, In-Game<h2><b>Playing:</b> <a href="' . $gameURL . '"><u>' . $extraInfo . '</u></a></h2>';
				$status2 = 'Online - Playing: ' . $extraInfo;
				$sColor = '#93be5b';
				$sGame = $extraInfo;
			} elseif ($sState == '2'){	$status = 'Busy';				$sColor = '#6186ad';
			} elseif ($sState == '3'){	$status = 'Away';				$sColor = '#e4944c';
			} elseif ($sState == '4'){	$status = 'Snooze';				$sColor = '#6186ad';
			} elseif ($sState == '5'){	$status = 'Looking to Trade';	$sColor = '#6186ad';
			} elseif ($sState == '6'){	$status = 'Looking to Play';	$sColor = '#6186ad';
			} elseif ($sState == '0'){	$status = 'Offline';			$sColor = '#d9666b';
			} else { $status = 'Steam Severs aren\'t Responding.';		$sColor = '#000000';
			}
			$output = "<a href=\"" . $profileURL . "\" id=\"steamsummery\" title=\"" . $status2 . "\" style=\"color:" . $sColor . ";font-weight:bold;\">" . $sName ."</a> - " . $status;
		}; 
		return $output;
	}
	
	// return users game list as a formatted table	
	function query_games_styled($sID64 = DEFAULTPROF) {
		$sID64 = $this->checkFor64Id($sID64); //TODO Catch NULL/FALSE
		$sXML = simplexml_load_file('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . APIKEY . '&steamid='.DEFAULTPROF.'&format=xml&include_appinfo=1');
		$output = "<table class=\"table table-striped table-bordered\"><tr><th>#</th><th></th><th>Games I Own</th><th>Playtime</th><th style=\"text-align:center;\">Store Link</th></tr>";
		$v = 0;
		foreach ($sXML->games->message as $data){
			$v++;
			$gamename = $data->name;
			$appid = $data->appid;
			$appurl = 'http://store.steampowered.com/app/' . $appid;
			$playtime = $data->playtime_forever;
			$imgLogoURL = $data->img_logo_url;
			if($playtime >= 60){
				$playtime = round(($playtime / 60), 1) . ' hours';
			}elseif($playtime == 0){
				$playtime = 'Haven\'t Played';
			}else{
				$playtime = $playtime . ' minutes';
			};
			if($imgLogoURL == ''){
				$gameImage = '<img src="css/noimage.jpg" height="69" width="184">';
			} else {
				$gameImage = '<img src="http://media.steampowered.com/steamcommunity/public/images/apps/'.$appid.'/'.$imgLogoURL.'.jpg" height="69" width="184">';
			}
			$output .= '<tr>
				<td>'.$v.'</td>
				<td style="padding:0px;width:184px;">'.$gameImage.'</td>
				<td style="vertical-align:middle"><h4><a href="' . $appurl . '">' . $gamename . '</a></h4></td>
				<td style="vertical-align:middle"><b>' . $playtime . '</b></td>
				<td style="text-align:center;vertical-align:middle;"><a href="' . $appurl . '" class="btn btn-default" role="button">Store Game Page</a></td></tr>';
		}
		$output .= '</table>';
		return $output;
	}
	
	// return users game list as an array
	function query_games($sID64 = DEFAULTPROF) {
		$sID64 = $this->checkFor64Id($sID64); //TODO Catch NULL/FALSE
		$sXML = simplexml_load_file('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . APIKEY . '&steamid='.$sID64.'&format=xml&include_appinfo=1');
		$output = array();
		$i = 0;
		foreach ($sXML->games->message as $data){
			$i++;
			$gamename = (string)$data->name;
			$appid = (string)$data->appid;
			$playtime = $data->playtime_forever;
			if($playtime >= 60){
				if(round(($playtime / 60), 1) == '1'){ $playtimeAgo = '1 hour';}
				else { $playtimeAgo = round(($playtime / 60), 1) . ' hours'; }
			}elseif($playtime > 1 && $playtime < 60){
				if($playtime == '1'){ $playtimeAgo = '1 minute';}
				else { $playtimeAgo = $playtime . ' minutes';}
			}else{
				$playtimeAgo = 'Haven\'t played';
			}
			$imgLogoURL = $data->img_logo_url;
			if($imgLogoURL == ''){
				$gameImage = NOIMAGE;
			} else {
				$gameImage = 'http://media.steampowered.com/steamcommunity/public/images/apps/'.$appid.'/'.$imgLogoURL.'.jpg';
			}
			$output[($i-1)] = array('number' => $i, 'name' => $gamename, 'appid' => $appid, 'playtimeAgo' => $playtimeAgo, 'gamelogourl' => $gameImage);
		}
		return $output;
	}
	
	// TODO - add a cache file 
	/*public function cacheCheck(){
		$cachefile = 'temp.cache';
		if (file_exists($cachefile)) {
			$cachestring = file_get_contents($cachefile, true);
			if ((now() - $time)$cachefile > CACHETIME) {$cachefile = file_get_contents($url)}
			return $file;
		} else {
			$handle = fopen($cachefile, 'w') or die('Cannot open file:  '.$cachefile); //implicitly creates file
			return false;
		}
	}*/
}
