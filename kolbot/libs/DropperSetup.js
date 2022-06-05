/**
*  @filename    DropperSetup.js
*  @author      theBGuy
*  @desc        Assign dropper settings and override necessary functions
*
*/
!isIncluded("common/prototypes.js") && include("common/prototypes.js");

const DropperAccounts = {
	// default mule logger profilenames
	"Logger1": {
		"account/password/realm": ["all"]
	},
	"Logger2": {
		"account/password/realm": ["all"]
	},
	"Logger3": {
		"account/password/realm": ["all"]
	},
	"Logger4": {
		"account/password/realm": ["all"]
	}
};

function parseDropperAccounts (accounts = [], chars = []) {
	for (let i in DropperAccounts) {
		if (DropperAccounts.hasOwnProperty(i) && typeof i === "string" && i.toLowerCase() === me.profile.toLowerCase()) {
			for (let j in DropperAccounts[i]) {
				if (DropperAccounts[i].hasOwnProperty(j) && typeof j === "string") {
					accounts.push(j);
					chars.push(DropperAccounts[i][j]);
				}
			}
		}
	}
}
