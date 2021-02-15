;(function ($, window, document) {
    // # - id
    // . - class

    /* TODO:
        - create array for dropper
        - add listener to drop button. creating drop.json via drop.php from POST data.

    */

    $(document).ready(function() {
        // Attach click event listener
        $("#opendropmenu").click(function(){
             // show Modal
             $('#myModal').modal('show');
        });

        // Attach click event listener for Trade List
        $("#tradelistmenu").click(function(){
             // show Modal
             $('#tradeListModal').modal('show');
        });

        $("div.mainmenu").on("click", function(e){
            e.preventDefault();
            var url = $(this).data('link');
            var aid = "#a" + $(this).data('aid');
            $.ajax({ 
                type: 'GET',
                url: url,
                aid: aid,
                beforeSend: function(){
                    $('.loader').show()
                },
                success: function(data) {
                    $(aid).html(data);
                    $('.loader').hide();
                }
            });
        });    

        // load items into table
        $("li.list-group-item").on("click", "a.submenu", function(e){
            e.preventDefault();
            var url = $(this).attr('href'); //get the link you want to load data from
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function(){
                    $('.loader').show()
                },
                success: function(data) {
                    $("#itemsoutput").html(data);
                    $('.loader').hide();
                }
            });
        });

        // load search result into table

        //to do: check if search field is not empty, add somehow "enter" key in input field
        $(".searchbut").click(function(e){
            var url = $(this).attr('url'); //get the link you want to load data from
            var str = $("form.searchform").serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: str,
                beforeSend: function(){
                    $('.loader').show()
                },
                success:  function(data){
                    $("#itemsoutput").html(data);
                    $('.loader').hide();
                }
            });
        });

        //bind enter key to button
        $('#searchtext').keypress(function(event) {
            if (event.keyCode == 13) {
                $(".searchbut").click();
                return false;
            }
        });

        $(function () {
            $("#searchtext").autocomplete({
                width: "90%",
				delay: 0,
                minLength: 1,
                maxHeight: 200,
                source: ''
            });

            $("#search_parameter").change(function () {
                var selectedSource = $(this).find("option:selected").val();
                var complete = {
                    runeword: [
                        "Ancients' Pledge",
                        "Beast", "Black", "Bone", "Bramble", "Brand", "Breath of the Dying",
                        "Call to Arms", "Chains of Honor", "Chaos", "Crescent Moon",
                        "Death", "Delirium", "Destruction", "Doom", "Dragon", "Dream", "Duress",
                        "Edge", "Enigma", "Enlightenment", "Eternity", "Exile",
                        "Faith", "Famine", "Fortitude", "Fury",
                        "Gloom", "Grief",
                        "Hand of Justice", "Harmony", "Heart of the Oak", "Holy Thunder", "Honor",
                        "Ice", "Infinity", "Insight",
                        "King's Grace", "Kingslayer",
                        "Last Wish", "Lawbringer", "Leaf", "Lionheart", "Lore",
                        "Malice", "Melody", "Memory", "Myth",
                        "Nadir",
                        "Oath", "Obedience",
                        "Passion", "Peace", "Phoenix", "Pride", "Principle", "Prudence",
                        "Radiance", "Rain", "Rhyme", "Rift",
                        "Sanctuary", "Silence", "Smoke", "Spirit", "Splendor", "Stealth", "Steel", "Stone", "Strength",
                        "Treachery",
                        "Venom", "Voice of Reason",
                        "Wealth", "White", "Wind", "Wrath",
                        "Zephyr"
                    ],
					uberkeys: [
						"Key of Terror", "Key of Hate", "Key of Destruction"
					],                    
					runes: [
                        "El Rune", "Eld Rune", "Tir Rune", "Nef Rune", "Eth Rune", "Ith Rune", "Tal Rune", "Ral Rune", "Ort Rune", "Thul Rune", "Amn Rune", "Sol Rune", "Shael Rune", "Dol Rune", "Hel Rune", "Io Rune", "Lum Rune", "Ko Rune", "Fal Rune", "Lem Rune", "Pul Rune", "Um Rune", "Mal Rune", "Ist Rune", "Gul Rune", "Vex Rune", "Ohm Rune", "Lo Rune", "Sur Rune", "Ber Rune", "Jah Rune", "Cham Rune", "Zod Rune"
                    ],
                    unique: [
                        "Alma Negra", "Andariel's Visage", "Annihilus", "Arachnid Mesh", "Arioc's Needle", "Arkaine's Valor", "Arm of King Leoric", "Arreat's Face", "Astreon's Iron Ward", "Athena's Wrath", "Atma's Scarab", "Atma's Wail", "Axe of Fechmar", "Azurewrath", 
						"Baezil's Vortex", "Bane Ash", "Baranar's Star", "Bartuc's Cut-Throat", "Biggin's Bonnet", "Bing Sz Wang", "Black Hades", "Blackbog's Sharp", "Blackhand Key", "Blackhorn's Face", "Blackleach Blade", "Blackoak Shield", "Blacktongue", "Blade Of Ali Baba", "Bladebone", "Bladebuckle", "Blastbark", "Blinkbat's Form", "Blood Crescent", "Blood Raven's Charge", "Bloodfist", "Bloodletter", "Bloodmoon", "Bloodrise", "Bloodthief", "Bloodtree Stump", "Boneflame", "Boneflesh", "Bonehew", "Boneshade", "Boneslayer Blade", "Bonesnap", "Brainhew", "Bul-Kathos' Wedding Band", "Buriza-Do Kyanon", "Butcher's Pupil", "Bverrit Keep", 
						"Carin Shard", "Carrion Wind", "Cerebus' Bite", "Chance Guards", "Chromatic Ire", "Cliffkiller", "Cloudcrack", "Coif of Glory", "Coldkill", "Coldsteel Eye", "Corpsemourn", "Crainte Vomir", "Cranebeak", "Crescent Moon", "Crow Caw", "Crown of Ages", "Crown of Thieves", "Crushflange", "Culwen's Point", 
						"Dark Clan Crusher", "Darkforce Spawn", "Darkglow", "Darksight Helm", "Death Cleaver", "Deathbit", "Death's Fathom", "Death's Web", "Deathspade", "Demon Limb", "Demon Machine", "Demonhorn's Edge", "Demon's Arch", "Dimoak's Hew", "Djinn Slayer", "Doombringer", "Doomslinger", "Dracul's Grasp", "Dragonscale", "Duriel's Shell", "Duskdeep", "Dwarf Star", 
						"Eaglehorn", "Earth Shifter", "Earthshaker", "Endlesshail", "Eschuta's Temper", "Ethereal Edge", "Executioner's Justice", 
						"Felloak", "Firelizard's Talons", "Flamebellow", "Fleshrender", "Fleshripper", "Frostburn", "Frostwind", 
						"Gargoyle's Bite", "Gerke's Sanctuary", "Gheed's Fortune", "Ghostflame", "Ghoulhide", "Giant Skull", "Gimmershred", "Ginther's Rift", "Gleamscythe", "Gloom's Trap", "Goblin Toe", "Goldskin", "Goldstrike Arch", "Goldwrap", "Gore Rider", "Gorefoot", "Goreshovel", "Gravenspine", "Gravepalm", "Greyform", "Griffon's Eye", "Grim's Burning Dead", "Griswold's Edge", "Guardian Angel", "Guardian Naga", "Gull", "Gut Siphon", 
						"Halaberd's Reign", "Hand of Blessed Light", "Harlequin Crest", "Hawkmail", "Head Hunter's Glory", "Headstriker", "Heart Carver", "Heavenly Garb", "Heaven's Light", "Hellcast", "Hellclap", "Hellfire Torch", "Hellmouth", "Hellplague", "Hellrack", "Hellslayer", "Herald Of Zakarum", "Hexfire", "Highlord's Wrath", "Homunculus", "Hone Sundan", "Horizon's Tornado", "Hotspur", "Howltusk", "Humongous", "Husoldal Evo", 
						"Iceblink", "Ichorsting", "Infernostride", "Iron Pelt", "Ironstone", "Islestrike", 
						"Jade Talon", "Jalal's Mane", 
						"Kelpie Snare", "Kinemil's Awl", "Kira's Guardian", "Knell Striker", "Kuko Shakaku", 
						"Lacerator", "Lance Guard", "Lance of Yaggai", "Langer Briser", "Lava Gout", "Leadcrow", "Lenymo", "Leviathan", "Lidless Wall", "Lightsabre", "Lycander's Aim", "Lycander's Flank", 
						"Maelstrom", "Magefist", "Magewrath", "Manald Heal", "Mang Song's Lesson", "Mara's Kaleidoscope", "Marrowwalk", "Medusa's Gaze", "Messerschmidt's Reaver", "Metalgrid", "Moonfall", "Moser's Blessed Circle", 
						"Nagelring", "Nature's Peace", "Nightsmoke", "Nightwing's Veil", "Nokozan Relic", "Nord's Tenderizer", "Nosferatu's Coil", 
						"Ondal's Wisdom", "Ormus' Robes", "Peasant Crown", 
						"Pelta Lunata", "Pierre Tombale Couant", "Plague Bearer", "Pluckeye", "Pompeii's Wrath", "Pus Spitter", 
						"Que-Hegan's Wisdom", 
						"Radament's Sphere", "Rainbow Facet", "Rakescar", "Rattlecage", "Raven Claw", "Raven Frost", "Ravenlore", "Razor's Edge", "Razorswitch", "Razortail", "Razortine", "Ribcracker", "Riphook", "Ripsaw", "Rixot's Keen", "Rockfleece", "Rockstopper", "Rogue's Bow", "Rune Master", "Rusthandle", 
						"Sandstorm Trek", "Saracen's Chance", "Schaefer's Hammer", "Seraph's Hymn", "Serpent Lord", "Shadow Dancer", "Shadow Killer", "Shadowfang", "Shaftstop", "Silks of the Victor", "Silkweave", "Skewer of Krintiz", "Skin of the Flayed One", "Skin of the Vipermagi", "Skull Collector", "Skull Splitter", "Skullder's Ire", "Skystrike", "Snakecord", "Snowclash", "Soul Drainer", "Soul Harvest", "Soulfeast Tine", "Soulflay", "Sparking Mail", "Spectral Shard", "Spellsteel", "Spike Thorn", "Spineripper", "Spire of Honor", "Spire of Lazarus", "Spirit Forge", "Spirit Keeper", "Spirit Ward", "Stealskull", "Steel Carapace", "Steel Pillar", "Steel Shade", "Steelclash", "Steeldriver", "Steelgoad", "Steelrend", "Stone Crusher", "Stone of Jordan", "Stoneraven", "Stormchaser", "Stormeye", "Stormguild", "Stormlash", "Stormrider", "Stormshield", "Stormspike", "Stormspire", "Stormstrike", "Stoutnail", "String of Ears", "Suicide Branch", "Sureshrill Frost", "Swordback Hold", "Swordguard", 
						"Tarnhelm", "Tearhaunch", "Templar's Might", "The Atlantean", "The Battlebranch", "The Cat's Eye", "The Centurion", "The Chieftain", "The Cranium Basher", "The Diggler", "The Dragon Chang", "The Eye of Etlich", "The Face of Horror", "The Fetid Sprinkler", "The Gavel Of Pain", "The General's Tan Do Li Ga", "The Gladiator's Bane", "The Gnasher", "The Grandfather", "The Grim Reaper", "The Hand of Broc", "The Impaler", "The Iron Jang Bong", "The Jade Tan Do", "The Mahim-Oak Curio", "The Meat Scraper", "The Minotaur", "The Oculus", "The Patriarch", "The Reaper's Toll", "The Redeemer", "The Rising Sun", "The Salamander", "The Scalper", "The Spirit Shroud", "The Tannr Gorerod", "The Vile Husk", "The Ward", "Thundergod's Vigor", "Thunderstroke", "Tiamat's Rebuke", "Titan's Revenge", "Todesfaelle Flamme", "Tomb Reaver", "Toothrow", "Torch of Iro", "Treads of Cthon", "Twitchthroe", "Tyrael's Might", 
						"Umbral Disk", "Ume's Lament", "Undead Crown", 
						"Valkyrie Wing", "Vampire Gaze", "Veil of Steel", "Venom Grip", "Venom Ward", "Verdungo's Hearty Cord", "Viperfork", "Visceratuant", 
						"Wall of the Eyeless", "War Traveler", "Warlord's Trust", "Warpspear", "Warshrike", "Waterwalk", "Widowmaker", "Windforce", "Windhammer", "Wisp Projector", "Witchwild String", "Witherstring", "Wizardspike", "Wizendraw", "Woestave", "Wolfhowl", "Wormskull", "Wraith Flight", 
						"Zakarum's Hand"
                    ],
					set: [
                        "Angelic Raiment", "Angelic Mantle", "Angelic Sickle", "Angelic Halo", "Angelic Wings", 
						"Arcanna's Tricks", "Arcanna's Head", "Arcanna's Flesh", "Arcanna's Deathwand", "Arcanna's Sign", 
						"Arctic Gear", "Arctic Furs", "Arctic Binding", "Arctic Mitts", "Arctic Horn", 
						"Berserker's Arsenal", "Berserker's Headgear", "Berserker's Hauberk", "Berserker's Hatchet", 
						"Cathan's Traps", "Cathan's Visage", "Cathan's Mesh", "Cathan's Rule", "Cathan's Sigil", "Cathan's Seal", 
						"Civerb's Vestments", "Civerb's Cudgel", "Civerb's Icon", "Civerb's Ward", 
						"Cleglaw's Brace", "Cleglaw's Tooth", "Cleglaw's Pincers", "Cleglaw's Claw", 
						"Death's Disguise", "Death's Touch", "Death's Hand", "Death's Guard", 
						"Hsarus' Defense", "Hsarus' Iron Fist", "Hsarus' Iron Stay", "Hsarus' Iron Heel", 
						"Infernal Tools", "Infernal Cranium", "Infernal Sign", "Infernal Torch",
						"Iratha's Finery", "Iratha's Coil", "Iratha's Collar", "Iratha's Cord", "Iratha's Cuff", 
						"Isenhart's Armory", "Isenhart's Lightbrand", "Isenhart's Horns", "Isenhart's Case", "Isenhart's Parry", 
						"Milabrega's Regalia", "Milabrega's Diadem", "Milabrega's Robe", "Milabrega's Orb", "Milabrega's Rod", 
						"Sigon's Complete Steel", "Sigon's Visor", "Sigon's Shelter", "Sigon's Sabot", "Sigon's Guard", "Sigon's Wrap", "Sigon's Gage", 
						"Tancred's Battlegear", "Tancred's Skull", "Tancred's Spine", "Tancred's Hobnails", "Tancred's Crowbill", "Tancred's Weird", 
						"Vidala's Rig", "Vidala's Barb", "Vidala's Ambush", "Vidala's Fetlock", "Vidala's Snare", 
						"Aldur's Watchtower", "Aldur's Stony Gaze", "Aldur's Advance", "Aldur's Deception", "Aldur's Rhythm", 
						"Bul-Kathos' Children", "Bul-Kathos' Sacred Charge", "Bul-Kathos' Tribal Guardian", 
						"Cow King's Leathers", "Cow King's Horns", "Cow King's Hide", "Cow King's Hooves", 
						"The Disciple", "Telling of Beads", "Laying of Hands", "Dark Adherent", "Rite of Passage", "Credendum", 
						"Griswold's Legacy", "Griswold's Heart", "Griswold's Valor", "Griswold's Redemption", "Griswold's Honor", 
						"Heaven's Brethren", "Haemosu's Adamant", "Dangoon's Teaching", "Taebaek's Glory", "Ondal's Almighty", 
						"Hwanin's Majesty", "Hwanin's Splendor", "Hwanin's Justice", "Hwanin's Refuge", "Hwanin's Blessing", 
						"Immortal King", "Immortal King's Will", "Immortal King's Stone Crusher", "Immortal King's Soul Cage", "Immortal King's Detail", "Immortal King's Forge", "Immortal King's Pillar", 
						"M'avina's Battle Hymn", "M'avina's True Sight", "M'avina's Caster", "M'avina's Embrace", "M'avina's Icy Clutch", "M'avina's Tenet", 
						"Natalya's Odium", "Natalya's Totem", "Natalya's Mark", "Natalya's Shadow", "Natalya's Soul", 
						"Naj's Ancient Vestige", "Naj's Circlet", "Naj's Light Plate", "Naj's Puzzler", 
						"Orphan's Call", "Guillaume's Face", "Whitstan's Guard", "Magnus' Skin", "Wilhelm's Pride", 
						"Sander's Folly", "Sander's Paragon", "Sander's Superstition", "Sander's Taboo", "Sander's Riprap", 
						"Sazabi's Grand Tribute", "Sazabi's Mental Sheath", "Sazabi's Cobalt Redeemer", "Sazabi's Ghost Liberator", 
						"Tal Rasha's Wrappings", "Tal Rasha's Lidless Eye", "Tal Rasha's Horadric Crest", "Tal Rasha's Guardianship", "Tal Rasha's Fine-Spun Cloth", "Tal Rasha's Adjudication", 
						"Trang-Oul's Avatar", "Trang-Oul's Guise", "Trang-Oul's Scales", "Trang-Oul's Wing", "Trang-Oul's Girth", "Trang-Oul's Claws"
                    ],
                };
                if (complete.hasOwnProperty(selectedSource)) {
                    $("#searchtext").autocomplete({
                        source: function(request, response) {
                            var results = $.ui.autocomplete.filter(complete[selectedSource], request.term);
                            response(results.slice(0, 10));
                        }
                    });
                } else {
                    $("#searchtext").autocomplete({
                        source: ''
                    });
                }

            }).change();
        });
		
		// Handle theme loading and saving.
		$("a.themeselect").click(function(e){ 
			e.preventDefault();
			
			var theme = $(this).attr('theme');
            $.ajax({
                type: 'POST',
                url: 'theme.php',
                data: {theme: theme},
                beforeSend: function(){
                    $('.loader').show()
                },
                success:  function(data){
					$("head link#layout1").attr("href", "themes/" + theme + "/css/bootstrap.css");
					$("head link#layout2").attr("href", "themes/" + theme + "/css/itemManager.css");
					$("head link#layout3").attr("href", "themes/" + theme + "/css/tooltipster.css");
					var a = $("head link#layout4");
					if (a)
						$("head link#layout4").attr("href", "themes/" + theme + "/css/jquery-ui.css");
                    $('.loader').hide();
                }
            });
		});
		
    });

    //bind ajax call to drop form
    $(function() {
        //bind function to form on submit request (enter key and button)
        $('form.dropfunction').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                beforeSend: function(){
                    $('.loader').show();
                },
                success: function (data, status) {
                    hideid = hideid.concat(rowsid);
                    show = false;
                    show = [];
                    rowsid = false;
                    rowsid	= [];
                    droparray = false;
                    droparray = [];

                    showHighligh();

                    //show back into clean arrays
                    $("#droplist").html(data);
                    document.getElementById('dropitem').value = "";

                    // Update tradelist info so we don't add items we don't have to list
                    $("#tradelist").html("");
                    document.getElementById('listinfo').value = "";

                    $('.loader').hide();
                },
                error: function (xhr, desc, err) {
                    alert(err);
                    $('.loader').hide();
                }
            });
        });

        //trademodal send
        $('form.listfunction').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                beforeSend: function(){
                    $('.loader').show();
                },
                success: function (data, status) {
                    $("#tradelist").html(data);
                    document.getElementById('listinfo').value = "";
                    $('.loader').hide();
                },
                error: function (xhr, desc, err) {
                    alert(err);
                    $('.loader').hide();
                }
            });
        });
    });


    // attach item upload to right mouse button
    $(document).on("contextmenu", function(e){
        e.preventDefault();
        //img with background
        var img = $('div.tooltipster-base');
        //img w/o background
        //img = $('div.tooltipster-content');
        if (img.length > 0) {
            imgurUpload(img, false, false);
	}
        return false;
    });

})( jQuery, window, document );

//select div or any part of page and that function convert it to png and upload to imgur
function imgurUpload(img, save, md5) {
    html2canvas(img, {
        allowTaint: true,
        taintTest: false,
        //logging: true,
        onrendered: function(canvas) {
            //change png to octet-stream tell browser that data type need to be download
                //var data = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                //window.location.href = data;
			//alert(canvas);
            var base64 = canvas.toDataURL("image/png");
            if (save) {
                $.post("saveme.php", {filename: md5}, function (data) {
                    //alert(data);
                    if (data === "false") {
                        $.post("saveme.php", {data: base64.split(',')[1], filename: md5}, function (data) {
                            var filename = "trade\\" + data.replace(/["']/g, "");
                            //alert("CREATED: " + filename + " " + data);
                        });
                    }
                });
            } else {
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);

                            $("div.uploading-image").css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
                            $('div.uploading-image').html("sending to imgur");

                            console.log(percentComplete);

                            if (percentComplete < 100) {

                            }

                        }
                        }, false);

                        return xhr;
                    },
                    url: 'https://api.imgur.com/3/image',
                    type: 'POST',
                    headers: {
                        Authorization: 'Client-ID b91bee2ff90a92d',
                        Accept: 'application/json'
                    },
                    data: {
                        // convert the image data to base64
                        image:  base64.split(',')[1],
                        type: 'base64'
                    },
                    beforeSend: function() {
                        //change to image upload
                        $('div.upload-progress').show();
                        $('div.uploading-image').css('width', '20%').attr('aria-valuenow', 20);
                        $('div.uploading-image').html("preparing image");
                    },
                    success: function(result) {
                        //what to do if successfull upload
                        var url = 'https://i.imgur.com/' + result.data.id + '.png';
                        $('div.uploading-image').html("<a href='" + url + "' target='blank'><b>" + url + "</b></a>");
                        $('div.upload-progress').delay(5000).hide(500);
                    },
                    error: function (xhr, desc, err) {
                        $('div.uploading-image').html(desc);
                        $('div.upload-progress').delay(5000).hide(500);
                    }
                });
            }
        }
    });
}
function CheckDrops() {
    $.ajax({
        type:'GET',
        url: 'countdrops.php',
        success: function(data) {
            if (data == 0) {
                $("#dropCounts").html('');
            } else {
                $("#dropCounts").html('<div class="diablo" style="margin-left:25px;margin-top:5px;font-weight:bold">Dropping ' + data + ' item(s) in progress...</div>');
            }
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
    setTimeout("CheckDrops()", 5000);
}
CheckDrops();
