/**
*	@filename		ItemDB.js
*	@author			dzik
*	@desc			function to add/update items into database
*	@thankYou		Richard for inspiring me,
					Gagget for all SQLite explains,
					Adhd for password find function.
*	@version		2021/01/30
**/

var ItemDB = {
	skipEquiped: true, // skip equipped items in logging
	mulePass: "", // default password if its not found anywhere else
	
	// DON'T TOUCH
	DB: "databases/ItemDB.s3db",
	logFile: "databases/ItemDB.log",
	query: "",
	DBConnect: 0,
	ID: {},
	tick: 0,
	count: 0,
	single: [],
	
	DBTblAccs:		"muleAccounts (accountRealm, accountLogin, accountPasswd)",
	DBTblChars:		"muleChars (charAccountId, charName, charExpansion, charHardcore, charLadder, charClassId)",
	DBTblItems:		"muleItems (itemCharId, itemName, itemType, itemClass, itemClassid, itemQuality, itemFlag, itemColor, itemImage, itemMD5, itemDescription, itemLocation, itemX, itemY)",
	DBTblStats:		"muleItemsStats (statsItemId, statsName, statsValue)",
	
	realms: { "uswest": 0, "useast": 1, "asia": 2, "europe": 3 },
	
	log: function (data) {
		var date = new Date(),
		h = date.getHours(),
		m = date.getMinutes(),
		s = date.getSeconds(),
		y = date.getFullYear(),
		mo = date.getMonth()+1,
		d = date.getDate(),
		timestamp = "[" + y + "." + (mo < 10 ? "0" + mo : mo) + "." + (d < 10 ? "0" + d : d) + " " + (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s) + "] ";
		Misc.fileAction(this.logFile, 2, timestamp + " - [ profile: \"" + me.profile + "\" account: \"" + me.account + "\" char: \"" + me.name + "\" ] " + data + "\n");
	},
	
	init: function (drop) {
		var success = true;
		if (this.createDB()) {
			print("ItemDB :: New database created!");
		}
		try {
			if(!drop)
				print("ItemDB :: Starting database connection");
			
			this.tick 		= getTickCount();
			
			//init db connection and open it. this is our handler now.	
			this.DBConnect	= new SQLite(this.DB, true);
			this.DBConnect.execute("BEGIN TRANSACTION;");	
			this.ID.acc 	= this.insertAccs(!drop);
			this.ID.chara	= this.insertChar();
			this.logItems(drop);
			this.DBConnect.execute("COMMIT;");			
		} catch (e) { 
			success = false;
			this.log(e);
		} finally {
			this.DBConnect.close();
		}
		
		if(!drop) {
			print("ItemDB :: Closing database connection after: " + ((getTickCount() - this.tick) / 1000).toFixed(2) + "s");
			this.log(this.count + " items logged in " + ((getTickCount() - this.tick) / 1000).toFixed(2) + "s");
		}
		
		return success;			
	},
	
	deleteChar: function(a) {
		var success = true;
		try {
			this.DBConnect	= new SQLite(this.DB, true);
			this.DBConnect.execute("BEGIN TRANSACTION;");
			this.DBConnect.execute("DELETE FROM muleChars WHERE charName = '" + a + "';")
			this.DBConnect.execute("COMMIT;");			
		} catch (e) { 
			success = false;
			this.log(e);
		} finally {
			this.DBConnect.close();
		}		
		return success;		
	},
	
	createDB: function () {
		var i, folder, data = [
			"PRAGMA main.page_size=4096;",
			"PRAGMA main.cache_size=10000;",
			"PRAGMA main.locking_mode=EXCLUSIVE;",
			"PRAGMA main.synchronous=NORMAL;",
			"PRAGMA main.journal_mode=WAL;",
			"PRAGMA main.temp_store = MEMORY;",
			"CREATE TABLE IF NOT EXISTS [muleAccounts] ([accountId] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT, [accountRealm] INTEGER  NULL, [accountLogin] VARCHAR(32)  NULL, [accountPasswd] VARCHAR(32)  NULL);",
			"CREATE TABLE IF NOT EXISTS [muleChars] ([charId] INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL, [charAccountId] INTEGER  NULL, [charName] VARCHAR(32)  NULL, [charExpansion] BOOLEAN  NULL, [charHardcore] BOOLEAN  NULL, [charLadder] BOOLEAN  NULL, [charClassId] INTEGER NULL);",
			"CREATE TABLE IF NOT EXISTS [muleItems] ([itemId] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT, [itemCharId] INTEGER  NULL, [itemName] VARCHAR(64)  NULL, [itemType] INTEGER  NULL, [itemClass] INTEGER  NULL, [itemClassid] INTEGER  NULL, [itemQuality] INTEGER  NULL, [itemFlag] INTEGER  NULL, [itemColor] INTEGER  NULL, [itemImage] VARCHAR(8)  NULL, [itemMD5] VARCHAR(32)  NULL, [itemDescription] TEXT  NULL, [itemLocation] INTEGER  NULL, [itemX] INTEGER NULL, [itemY] INTEGER NULL);",
			"CREATE TABLE IF NOT EXISTS [muleItemsStats] ([statsItemId] INTEGER  NULL, [statsName] VARCHAR(50)  NULL, [statsValue] INTEGER  NULL);",
			"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULEACCOUNTS_ACCOUNTID] ON [muleAccounts]([accountRealm]  ASC, [accountLogin]  ASC);",
			"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULECHARS_CHARID] ON [muleChars]([charAccountId]  ASC, [charName]  ASC);",
			"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULEITEMS_ITEMID] ON [muleItems]([itemId]  ASC, [itemCharId]  ASC);",
			"CREATE UNIQUE INDEX IF NOT EXISTS [IDX_MULEITEMSSTATS_STATSITEMID] ON [muleItemsStats]([statsItemId]  ASC,[statsName]  ASC);",
			"CREATE TRIGGER [ON_TBL_MULEACCOUNTS_DELETE] BEFORE DELETE ON [muleAccounts] FOR EACH ROW BEGIN DELETE FROM muleChars WHERE charAccountId = OLD.accountId; END;",
			"CREATE TRIGGER [ON_TBL_MULECHARS_DELETE] BEFORE DELETE ON [muleChars] FOR EACH ROW BEGIN DELETE FROM muleItems WHERE itemCharId = OLD.charId; END;",
			"CREATE TRIGGER [ON_TBL_MULEITEMS_DELETE] BEFORE DELETE ON [muleItems] FOR EACH ROW BEGIN DELETE FROM muleItemsStats WHERE statsItemId = OLD.itemId; END;"			
			];
		
		if (!FileTools.exists(this.DB)){
			if (!FileTools.exists("databases")) {
				folder = dopen("");
				folder.create("databases");			
			}
			this.DBConnect = new SQLite(this.DB, true);
			
			for (i = 0; i < data.length; i++) {
				this.DBConnect.execute(data[i]);
			}
			
			this.DBConnect.close();
			
			return true;
		} else {
			return false;
		}
	},
	
	insertAccs: function(update) {
		var handle, accID, accPW;
		//realm, account
		
		this.getPasswords();
		
		handle = this.DBConnect.query("SELECT accountId, accountPasswd FROM muleAccounts WHERE accountLogin = '" + me.account.toLowerCase() + "' AND accountRealm = '" + this.realms[me.realm.toLowerCase()] + "';");
		handle.next();
		
		if (handle.ready) {
			accID = handle.getColumnValue(0);
			accPW = handle.getColumnValue(1);
		} else {
			while (!this.DBConnect.execute("INSERT into " + this.DBTblAccs + " values ('" + this.realms[me.realm.toLowerCase()] + "', '" + me.account.toLowerCase() + "', '" + this.mulePass + "');")) {
					delay(500);
			}
			accID = this.DBConnect.lastRowId;
			print("ItemDB :: Added account \"" + me.account + "\" into database with ID: " + accID);
		}
		
		if (!accPW && this.mulePass != accPW && update) {
			while (!this.DBConnect.execute("UPDATE muleAccounts SET accountPasswd = '" + this.mulePass + "' WHERE accountId = " + accID + ";")) {
					delay(500);
			}
			this.log("Updated password for: \"" + me.account + "\" old: \"" + accPW + "\" new: \"" + this.mulePass + "\" ");
		}
		
		return accID;	
	},
	
	insertChar: function() {
		var handle, charID, charClass;
		// id, me.name, me.gametype, me.playertype, me.ladder, me.classid
		
		this.ID.exp		=	me.gametype ? "1" : "0";
		this.ID.sc		=	me.playertype ? "1" : "0";
		this.ID.lad		=	me.ladder ? "1" : "0";
		this.ID.classId	=	me.classid;
		
		handle = this.DBConnect.query("SELECT charId, charClassId FROM muleChars WHERE charAccountId = '" + this.ID.acc + "' AND charName = '" + me.name + "';");
		handle.next();
		
		if (handle.ready) {
			charID 		= handle.getColumnValue(0);
			charClass	= handle.getColumnValue(1);
			
			while (!this.DBConnect.execute("UPDATE muleChars SET charClassId = " + this.ID.classId + " WHERE charId = " + charID + ";")) {
					delay(500);
			}
			
		} else {
			while (!this.DBConnect.execute("INSERT INTO " + this.DBTblChars + " VALUES ('" + this.ID.acc + "', '" + me.name + "', '" + this.ID.exp + "', '" + this.ID.sc + "', '" + this.ID.lad + "', '" + this.ID.classId + "');")) {
				delay(500);
			}
			charID = this.DBConnect.lastRowId;
			print("ItemDB :: added character \"" + me.name + "\" into database with ID: " + charID);
		}
		
		return charID;	
	},
	
	logItems: function (dd) {
		var items, i;
		
		if(dd) {
			var handle, itemid, dropitam;
			// id, me.name, me.gametype, me.playertype, me.ladder
			
			if (typeof dd === "string") {
				dd = [dd];
			}
			
			for (i = 0; i < dd.length; i++) {
				handle = this.DBConnect.query("SELECT itemId,itemName FROM muleItems LEFT JOIN muleChars ON itemCharId = charId WHERE charAccountId = '" + this.ID.acc + "' AND charName = '" + me.name + "' AND itemMD5 = '" + dd[i] + "' LIMIT 1;");
				handle.next();
				
				if (handle.ready) {
					itemid = handle.getColumnValue(0);
					dropitam = handle.getColumnValue(1);
				}

				if (typeof itemid != "number") {
					this.log("RELOG CHARACTER - DATA CORRUPTED");
					return false;
				}
				
				this.DBConnect.execute("DELETE FROM muleItems WHERE itemId = " + itemid + ";");
				
				this.log("dropped " + dropitam + " in " + me.gamename + "//" + me.gamepassword);
			}	
			print("ItemDB :: removed " + dd.length + " items from database.");
			return true;
		}
		//remove items from DB with your charID to avoid double entrys
		while(!this.DBConnect.execute("DELETE FROM muleItems WHERE itemCharId = " + this.ID.chara + ";")) {
			delay(500);
		}
		
		
		//list of our items
		items 	= me.getItems();
		
		for (i = 0; i < items.length; i++) {
			if ([22, 76, 77, 78].indexOf(items[i].itemType) === -1) {	//skip scrools and potions
				if(items[i].mode === 1 && this.skipEquiped) {
					continue;
				}
				this.ID.item 	= 	this.insertItem(items[i]);
				this.insertStats(items[i]);
				this.count++;
			}
		}
		return true;
	
	},
	
	insertItem: function (item) {
		var handle, itam = {}, itemID;
		//itemchar, itemname, itemtype, itemclass, itemclassid, itemquality, itemflag, itemcolor, itemimage, itemdesc
		itam.fname 			= this.safeStrings(item.fname.split("\n").reverse().join(" ").replace(/(y|ÿ)c[0-9!"+<;.*]/, "").trim());
		itam.flag			= item.getFlags();
		itam.color			= item.getColor();
		itam.image			= this.getImage(item);
		itam.MD5 			= md5(item.description);
		itam.description	= this.safeStrings(this.getItemDesc(item));
		
		while(!this.DBConnect.execute("INSERT INTO " + this.DBTblItems + " VALUES ('" + this.ID.chara + "', '" + itam.fname + "', '" + item.itemType + "', '" + item.itemclass + "', '" + item.classid + "', '" + item.quality + "', '" + itam.flag + "', '" + itam.color + "', '" + itam.image + "', '" + itam.MD5 + "', '" + itam.description + "', '" + item.location + "', '" + item.x + "', '" + item.y + "');")) {
			delay(500);
		}
		itemID = this.DBConnect.lastRowId;
		
		return itemID;
	},
	
	insertStats: function (item) {
		var a, stats;
		
		stats = this.dumpItemStats(item);
		
		for (a in stats) {
			while(!this.DBConnect.execute("INSERT INTO " + this.DBTblStats + " VALUES ('" + this.ID.item + "', '" + a + "', '" + stats[a] + "');")){
				delay(500);
			}
		}		
	},
	
	dumpItemStats: function (item) {	//ty kolton
		var val, i, n,
			stats = item.getStat(-2),
			dump = {};

		for (i = 0; i < stats.length; i += 1) {
			if (stats[i]) {
				for (n in NTIPAliasStat) {
					if (NTIPAliasStat.hasOwnProperty(n)) {
						switch (typeof NTIPAliasStat[n]) {
						case "number":
							if (NTIPAliasStat[n] === i) {
								switch (NTIPAliasStat[n]) {
								case 20: // toblock
								case 21: // mindamage
								case 22: // maxdamage
								case 23: // secondarymindamage
								case 24: // secondarymaxdamage
								case 31: // defense
								case 83: // itemaddclassskills
								case 188: // itemaddskilltab
								case 159: // itemthrowmindamage
								case 160: // itemthrowmaxdamage
									val = item.getStatEx(NTIPAliasStat[n]);

									if (val) {
										dump[n] = val;
									}

									break;
								// poison damage stuff
								case 57: // poisonmindam
								case 58: // poisonmaxdam
								case 59: // poisonlength
								case 326: // poisoncount
									if (!dump.hasOwnProperty("poisondamage")) {
										val = item.getStatEx(57, 1);

										if (val) {
											dump.poisondamage = val;
										}
									}

									break;
								case 195:
								case 198:
								case 204:
									if (stats[i]) {
										dump[n] = stats[i].skill;
									}

									break;
								default:
									if (stats[i][0]) {
										dump[n] = stats[i][0];
									}

									break;
								}
							}

							break;
						case "object":
							val = item.getStatEx(NTIPAliasStat[n][0], NTIPAliasStat[n][1]);
							if (val) {
								dump[n] = val;
							}
							break;
						}
					}
				}
			}
		}

		return dump;
	},
	
	getImage: function (unit) {
		//copy from kolbot
		var code, i;
		switch (unit.quality) {
			case 5: // Set
				switch (unit.classid) {
				case 27: // Angelic sabre
					code = "inv9sbu";

					break;
				case 74: // Arctic short war bow
					code = "invswbu";

					break;
				case 308: // Berserker's helm
					code = "invhlmu";

					break;
				case 330: // Civerb's large shield
					code = "invlrgu";

					break;
				case 31: // Cleglaw's long sword
				case 227: // Szabi's cryptic sword
					code = "invlsdu";

					break;
				case 329: // Cleglaw's small shield
					code = "invsmlu";

					break;
				case 328: // Hsaru's buckler
					code = "invbucu";

					break;
				case 306: // Infernal cap / Sander's cap
					code = "invcapu";

					break;
				case 30: // Isenhart's broad sword
					code = "invbsdu";

					break;
				case 309: // Isenhart's full helm
					code = "invfhlu";

					break;
				case 333: // Isenhart's gothic shield
					code = "invgtsu";

					break;
				case 326: // Milabrega's ancient armor
				case 442: // Immortal King's sacred armor
					code = "invaaru";

					break;
				case 331: // Milabrega's kite shield
					code = "invkitu";

					break;
				case 332: // Sigon's tower shield
					code = "invtowu";

					break;
				case 325: // Tancred's full plate mail
					code = "invfulu";

					break;
				case 3: // Tancred's military pick
					code = "invmpiu";

					break;
				case 113: // Aldur's jagged star
					code = "invmstu";

					break;
				case 234: // Bul-Kathos' colossus blade
					code = "invgsdu";

					break;
				case 372: // Grizwold's ornate plate
					code = "invxaru";

					break;
				case 366: // Heaven's cuirass
				case 215: // Heaven's reinforced mace
				case 449: // Heaven's ward
				case 426: // Heaven's spired helm
					code = "inv" + unit.code + "s";

					break;
				case 357: // Hwanin's grand crown
					code = "invxrnu";

					break;
				case 195: // Nalya's scissors suwayyah
					code = "invskru";

					break;
				case 395: // Nalya's grim helm
				case 465: // Trang-Oul's bone visage
					code = "invbhmu";

					break;
				case 261: // Naj's elder staff
					code = "invcstu";

					break;
				case 375: // Orphan's round shield
					code = "invxmlu";

					break;
				case 12: // Sander's bone wand
					code = "invbwnu";

					break;
				}

				break;
			case 7: // Unique
				for (i = 0; i < 401; i += 1) {
					if (unit.fname.split("\n").reverse()[0].indexOf(getLocaleString(getBaseStat(17, i, 2))) > -1) {
						code = getBaseStat(17, i, "invfile");

						break;
					}
				}

				break;
		}

		if (!code) {
			if (["ci2", "ci3"].indexOf(unit.code) > -1) { // Tiara/Diadem
				code = unit.code;
			} else {
				code = getBaseStat(0, unit.classid, 'normcode') || unit.code;
			}

			code = code.replace(" ", "");

			if ([10, 12, 58, 82, 83, 84].indexOf(unit.itemType) > -1) {
				code += (unit.gfx + 1);
			}
		}
		
		return code;
	},
	
	safeStrings: function(string) {
		string = string.replace(/[\0\n\r\b\t\\'"\x1a]/g, function (s) {
			switch (s) {
				case "\0":
					return "\\0";
				case "\n":
					return "\\n";
				case "\r":
					return "\\r";
				case "\b":
					return "\\b";
				case "\t":
					return "\\t";
				case "\x1a":
					return "\\Z";
				case "'":
					return "''";
				case '"':
					return '""';
				default:
					return "\\" + s;
			}
		});
		
		return string;
	},
	
	getItemDesc: function (unit) {
		var i, desc,
			stringColor = "<span class='color0'>";

		desc = unit.description;

		if (!desc) {
			return "";
		}

		desc = desc.split("\n");

		// Lines are normally in reverse. Add color tags if needed and reverse order.
		for (i = 0; i < desc.length; i += 1) {
			if (desc[i].indexOf(getLocaleString(3331)) > -1) { // Remove sell value
				desc.splice(i, 1);

				i -= 1;
			} else {
				if (desc[i].match(/^(y|ÿ)c/)) {
					stringColor = desc[i].substring(0, 3);
				} else {
					desc[i] = stringColor + desc[i];
				}
			}

			desc[i] = desc[i].replace(/(y|ÿ)c([0-9!"+<;.*])/g, "<span class='color$2'>");
			desc[i] = desc[i] + "</span>";
			if (stringColor == "<span class='color0'>") {	//What a dirty solution O.o
				desc[i] = desc[i] + "</span>";
			}
			
		}

		if (desc[desc.length - 1]) {
			desc[desc.length - 1] = desc[desc.length - 1].trim() + " (" + unit.ilvl + ")";
		}

		desc = desc.reverse().join("<BR>");

		return desc;
	},
	
	getPasswords: function() {	//ty Adhd
		var i;
		
		if (!isIncluded("MuleLogger.js")) {
			include("MuleLogger.js");
		}

		for (i in MuleLogger.LogAccounts) {
			if (MuleLogger.LogAccounts.hasOwnProperty(i) && typeof i === "string") {
				for (var j in MuleLogger.LogAccounts[i]) {
					if (MuleLogger.LogAccounts[i].hasOwnProperty(j) && typeof j === "string") {
						if (j.split("/")[0].toLowerCase() === me.account.toLowerCase()) {
							this.mulePass = j.split("/")[1];
							return true;
						}
					}					
				}
			}
		}
		
		if (!isIncluded("AutoMule.js")) {
			include("AutoMule.js");
		}
		
		for (i in AutoMule.Mules) {
			if (AutoMule.Mules[i].accountPrefix) {
				if (me.account.toLowerCase().match(AutoMule.Mules[i].accountPrefix.toLowerCase())) {
					this.mulePass = AutoMule.Mules[i].accountPassword;
					return true;
				}
			}
		}
		
		for (i in AutoMule.TorchAnniMules) {
			if (AutoMule.TorchAnniMules[i].accountPrefix) {
				if (me.account.toLowerCase().match(AutoMule.TorchAnniMules[i].accountPrefix.toLowerCase())) {
					this.mulePass = AutoMule.TorchAnniMules[i].accountPassword;
					return true;
				}
			}
		}
		
		return false;
	},
	
	deleteCharacter: function(obj) {
		var success = true;
		try {
			print("ItemDB :: Starting database connection");
			
			this.tick 		= getTickCount();
			
			//init db connection and open it. this is our handler now.	
			this.DBConnect	= new SQLite(this.DB, true);
			this.DBConnect.execute("BEGIN TRANSACTION;");
			this.DBConnect.execute("DELETE FROM muleChars WHERE charName = '" + obj.charName + "';");
			this.DBConnect.execute("COMMIT;");
			
			print("ItemDB :: Starting database connection");
		} catch (e) { 
			success = false;
			this.log(e);
		} finally {
			this.DBConnect.close();
		}
		
		return success;
	}
};