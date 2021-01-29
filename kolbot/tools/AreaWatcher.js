/**
*	@filename	AreaWatcher.js
*	@author		dzik
*/

function main() {
	include("oog.js");
	
	while (true) {
		var badAreas = [2];
		try {
			if (me.ingame && me.gameReady && badAreas.indexOf(me.area) >= 0) {
				D2Bot.printToConsole("Saved from suicide walk!");
				D2Bot.restart();
			}
		} catch (e) {
			print("AreaWatcher failed somewhere.");
		}
		delay(1000);
	}
}