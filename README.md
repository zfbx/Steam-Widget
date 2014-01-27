SteamWidget
===========

PHP utilization of Steams API calls


To call username for current online status use these lines
```
  $steamwidget = new SteamWidget();
	echo $steamwidget->current_steam_status();
```
It will echo out current online status (online, offline, away, in-game).
If user is in game it will print out the game title as a link to get the game in the store.


TODO
- [X] Add ability to convert SteamID to SteamID64.
- [ ] Create ability to cache recent game list calls to increase page load speed.
