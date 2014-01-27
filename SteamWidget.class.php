<?php

//------------------------------------------------
//		STEAM WIDGET CONFIGURATION 
//------------------------------------------------
define("APIKEY", "################################"); // Aquire at http://steamcommunity.com/dev/apikey
define("DEFAULTPROFILE", "76561198016593929"); //default profile to use, currently set to the developers.

//------------------------------------------------
//		STEAM WIDGET
//------------------------------------------------
class SteamWidget{
	
	public function ago($i){//Last updated funtion (send it ago(timestamp) **use time() to get that
		$m = time()-$i; $o='just now';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
		}return $o;
	}

	public function current_steam_status() { 
		$tbSteamXml = simplexml_load_file('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . APIKEY . '&format=xml&steamids='.DEFAULTPROFILE);
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

	
	function tb_download_database() {	
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
}