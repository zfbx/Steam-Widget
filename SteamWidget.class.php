<?php

//------------------------------------------------
//		STEAM WIDGET CONFIGURATION 
//------------------------------------------------
define("APIKEY", "###############################"); 	// Aquire at http://steamcommunity.com/dev/apikey
define("DEFAULTPROFILE", "76561198016593929"); 			//default profile to use, currently set to the developers.
//define("CACHE", true); 								//cahce content for ## minute
//define("CACHETIME", 300); 							//time between updates (unix timestamp @ 5min)
define("NOIMAGE", "css/noimage.jpg");					// fallback game logo image for games without a logo

//------------------------------------------------
//		STEAM WIDGET
//------------------------------------------------
class SteamWidget{

	// TODO - add a cache file 
	/*public function cacheCheck(){
		$cachefile = 'temp.cache';
		if (file_exists($cachefile)) {
			$cachestring = file_get_contents($cachefile, true);
			if ((now() - $time)$cachefile > CACHETIME) {
			$cachefile = file_get_contents($url)
			}
			return $file;
		} else {
			$handle = fopen($cachefile, 'w') or die('Cannot open file:  '.$cachefile); //implicitly creates file
			return false;
		}
	}*/

	
	public function get64Id($profileurl = null){
		if($profileurl == null){
			return DEFAULTPROFILE; //if nothing submitted, use the creators steam ID
		} else {
			if(strpos($profileurl, '/') != false) {
				$stripped = preg_replace('#^https?://#', '', $profileurl);
				//echo $stripped; //debugging
				$split = explode("/", $stripped);
				//print_r($split); //debugging
				$profileurl = $split[2];
				//echo $profileurl; //debugging
			}
			$steamXml = simplexml_load_file('http://steamcommunity.com/id/'.$profileurl.'/?xml=1');
			$steamId64 = $steamXml->steamID64;
			if ($steamId64 != ''){
				return $steamId64;
			} else {
				return false;
			}
		}
	}
	
	//Check for ID or url and convert or passthrough ID64
	public function checkFor64Id($var = null){
		if($var != null){
			if (strlen($var) == 17 && is_numeric($var)){
				return $var;
			} else {
				$check = $this->get64Id($var);
				return $check;
			}
		} else {
			return false;
		}
	}
	
	public function get64IdCovnert($profileurl = null){
		
		$steamId64 = $this->get64Id($profileurl);
		if ($steamId64 != ''){
			return '<div class="alert alert-success"> SteamID64 for <b>'.$profileurl.'</b> is <b>'.$steamId64.'</b></div>';

		} else {
			return '<div class="alert alert-danger"> There is no SteamID64 for <b>'.$profileurl.'</b></div>';
		}
		
	}
	
	public function ago($i){//Last updated funtion (send it ago(timestamp) **use time() to get that
		$m = time()-$i; $o='just now';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
		}return $o;
	}

	public function current_steam_status($steamID64 = DEFAULTPROFILE) {
		$steamID64 = $this->checkFor64Id($steamID64); //TODO Catch NULL/FALSE
		$tbSteamXml = simplexml_load_file('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . APIKEY . '&format=xml&steamids='.$steamID64);
		$tbSteamName = $tbSteamXml->players->player->personaname;
		if ($tbSteamName != "") {
			$tbSteamId = $tbSteamXml->players->player->steamid;
			$tbMemberSince = $this->ago($tbSteamXml->players->player->timecreated);
			$tbProfileUrl = $tbSteamXml->players->player->profileurl;
			$tbHoursPlayed = $tbSteamXml->hoursPlayed2Wk;
			$tbPersonaState = $tbSteamXml->players->player->personastate;
			$tbGameExtraInfo = $tbSteamXml->players->player->gameextrainfo;
			$tbGameId = $tbSteamXml->players->player->gameid;
			$tbStatusColor2 = '#ffffff';
			$tbStatusClean = '';
			
			if ($tbPersonaState == '1' && $tbGameId == ''):
				$tbStatus = 'Online<h2></h2>';
				$tbStatusColor = '#6186ad';
			elseif ($tbPersonaState == '1' && $tbGameId != ''):
				$tbSteamGameUrl = 'http://store.steampowered.com/app/' . $tbGameId;
				$tbStatus = 'Online, In-Game<h2><b>Playing:</b> <a href="' . $tbSteamGameUrl . '"><u>' . $tbGameExtraInfo . '</u></a></h2>';
				$tbStatusClean = 'Online - Playing: ' . $tbGameExtraInfo;
				$tbStatusColor = '#93be5b';
				$tbCurrentGame = $tbGameExtraInfo;
			elseif ($tbPersonaState == '2'):
				$tbStatus = 'Busy';
				$tbStatusColor = '#6186ad';
			elseif ($tbPersonaState == '3'):
				$tbStatus = 'Away';
				$tbStatusColor = '#e4944c';
			elseif ($tbPersonaState == '4'):
				$tbStatus = 'Snooze';
				$tbStatusColor = '#6186ad';
			elseif ($tbPersonaState == '5'):
				$tbStatus = 'Looking to Trade';
				$tbStatusColor = '#6186ad';
			elseif ($tbPersonaState == '6'):
				$tbStatus = 'Looking to Play';
				$tbStatusColor = '#6186ad';
			elseif ($tbPersonaState == '0'):
				$tbStatus = 'Offline';
				$tbStatusColor = '#d9666b';
			else:
				$tbStatus = 'Steam Severs aren\'t Responding.';
				$tbStatusColor = '#000000';
			endif;
			$tbSteamSummary = "<a href=\"" . $tbProfileUrl . "\" id=\"steamsummery\" title=\"" . $tbStatusClean . "\" style=\"color:" . $tbStatusColor . ";font-weight:bold;\">" . $tbSteamName ."</a> - " . $tbStatus;
		}; 
		return $tbSteamSummary;
	}

	
	function query_games_styled($steamID64 = DEFAULTPROFILE) {
		$steamID64 = $this->checkFor64Id($steamID64); //TODO Catch NULL/FALSE
		$tbSteamData = simplexml_load_file('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . APIKEY . '&steamid='.DEFAULTPROFILE.'&format=xml&include_appinfo=1');
		$tbSteamOutput = "<table class=\"table table-striped table-bordered\"><tr><th>#</th><th></th><th>Games I Own</th><th>Playtime</th><th style=\"text-align:center;\">Store Link</th></tr>";
		$v = 0;
		foreach ($tbSteamData->games->message as $Steamdata){
			$v++;
			$gamename = $Steamdata->name;
			$appid = $Steamdata->appid;
			$appurl = 'http://store.steampowered.com/app/' . $appid;
			$playtime = $Steamdata->playtime_forever;
			$imglogourl = $Steamdata->img_logo_url;
			if($playtime >= 60){
				$playtime = round(($playtime / 60), 1) . ' hours';
			}elseif($playtime == 0){
				$playtime = 'Haven\'t Played';
			}else{
				$playtime = $playtime . ' minutes';
			};
			if($imglogourl == ''){
				$gameimage = '<img src="css/noimage.jpg" height="69" width="184">';
			} else {
				$gameimage = '<img src="http://media.steampowered.com/steamcommunity/public/images/apps/'.$appid.'/'.$imglogourl.'.jpg" height="69" width="184">';
			}
			$tbSteamOutput .= '<tr>
				<td>'.$v.'</td>
				<td style="padding:0px;width:184px;">'.$gameimage.'</td>
				<td style="vertical-align:middle"><h4><a href="' . $appurl . '">' . $gamename . '</a></h4></td>
				<td style="vertical-align:middle"><b>' . $playtime . '</b></td>
				<td style="text-align:center;vertical-align:middle;"><a href="' . $appurl . '" class="btn btn-default" role="button">Store Game Page</a></td></tr>';
		}
		$tbSteamOutput .= '</table>';
		return $tbSteamOutput;
	}
	
	
	function query_games($steamID64 = DEFAULTPROFILE) {
		$steamID64 = $this->checkFor64Id($steamID64); //TODO Catch NULL/FALSE
		$tbSteamData = simplexml_load_file('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . APIKEY . '&steamid='.$steamID64.'&format=xml&include_appinfo=1');
		$output = array();
		$i = 0;
		foreach ($tbSteamData->games->message as $Steamdata){
			$i++;
			$gamename = (string)$Steamdata->name;
			$appid = (string)$Steamdata->appid;
			
			// Playtime/Playtimeago
			$playtime = $Steamdata->playtime_forever;
			if($playtime >= 60){
				if(round(($playtime / 60), 1) == '1'){ $playtimeago = '1 hour';}
				else { $playtimeago = round(($playtime / 60), 1) . ' hours'; }
			}elseif($playtime > 1 && $playtime < 60){
				if($playtime == '1'){ $playtimeago = '1 minute';}
				else { $playtimeago = $playtime . ' minutes';}
			}else{
				$playtimeago = 'Haven\'t played';
			}
			
			// Game logo icon url
			$imglogourl = $Steamdata->img_logo_url;
			if($imglogourl == ''){
				$gameimage = NOIMAGE;
			} else {
				$gameimage = 'http://media.steampowered.com/steamcommunity/public/images/apps/'.$appid.'/'.$imglogourl.'.jpg';
			}
			
			$output[($i-1)] = array('number' => $i, 'name' => $gamename, 'appid' => $appid, 'playtimeago' => $playtimeago, 'gamelogourl' => $gameimage);
		}
		return $output;
	}
}
