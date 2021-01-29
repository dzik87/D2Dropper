;(function ($, window, document) {
    // # - id
    // . - class

    // tr attributes: draccount, drchar, drmd5, drrealm, drname
    $(document).ready(function() {
        // highlight rows from array prevent double selecting same item

        showHighligh();

        //select/deselect item row
        $('#itemstable > tbody > tr').click(function () {
            var img = $('div.tooltipster-base');
            if (img) {
                imgurUpload(img, true, $(this).attr('drImage')+"-"+$(this).attr('drmd5'));
            }
            // select
            if($(this).hasClass("selecteditem") === false) {
                //change bg color
                //$(this).css('background', 'LightGray');
				$(this).addClass("selecteditem");

                // get tr attributes
                var data = $(this).attr('drname');
                var rowid = $(this).attr('dritemid');

                // push name to list
                show.push(data);
                rowsid.push(rowid);

                var dropdata = {
                    dropProfile: "",
                    realm: $(this).attr('drrealm'),
                    account: $(this).attr('draccount'),
                    charName: $(this).attr('drchar'),
                    itemType: $(this).attr('dritemtype'),
                    dropit: $(this).attr('drmd5'),
                    skin: $(this).attr('drImage'),
                    itemID: $(this).attr('drID')
                };

                var pushtodrop = JSON.stringify(dropdata);

                droparray.push(pushtodrop);

                var output = ParseSame(show);
                // update panel value
                document.getElementById('dropitem').value = JSON.stringify(droparray);
                $("#droplist").html(output);

                // update panel value
                document.getElementById('listinfo').value = JSON.stringify(droparray);
                $("#tradelist").html(output);

            }
            // deselect
            else {
                // set bg color
                //$(this).css('background', '');
				$(this).removeClass("selecteditem");

                //get tr attributes
                data = $(this).attr('drname');
                rowid = $(this).attr('dritemid');

                dropdata = {
                    dropProfile: "",
                    realm: $(this).attr('drrealm'),
                    account: $(this).attr('draccount'),
                    charName: $(this).attr('drchar'),
                    itemType: $(this).attr('dritemtype'),
                    dropit: $(this).attr('drmd5'),
                    skin: $(this).attr('drImage'),
                    itemID: $(this).attr('drID')
                };

                pushtodrop = JSON.stringify(dropdata);

                //search for item in array
                for (var i = 0; i < droparray.length; i += 1) {
                    if (droparray[i] == pushtodrop) {
                        droparray.splice(i, 1);
                        break;
                    }
                }

                //search for item in array
                for (var i = 0; i < show.length; i += 1) {
                    if (show[i] == data) {
                        show.splice(i, 1);
                        break;
                    }
                }

                //search for item in array
                for (var i = 0; i < rowsid.length; i += 1) {
                    if (rowsid[i] == rowid) {
                        rowsid.splice(i, 1);
                        break;
                    }
                }
                var output = ParseSame(show);
                // update panel value
                document.getElementById('dropitem').value = JSON.stringify(droparray);
                $("#droplist").html(output);

                // update panel value
                document.getElementById('listinfo').value = JSON.stringify(droparray);
                $("#tradelist").html(output);
            }
        });


        // function on click for 'showhide' class.
        $('.showhide').click(function() {
            /* locations related to kolbot:
                loc1 - body
                loc2 - belt
                loc3 - inventory
                loc5 - tradescreen
                loc6 - cube
                loc7 - stash
            */

            var text = $(this).html();

            // hide tr with class "loc1"
            if (text == "hide equiped") {
                $('.loc1').hide();
                $(this).html("show equiped");
            } else {
                $('.loc1').show();
                $(this).html("hide equiped");
            }
        });
    });

    // tooltip
    $('.show-tooltip').each(function () {
        var p = $(this).parent();
        if(p.is('td')) {
            /* if your tooltip is on a <td>, transfer <td>'s padding to wrapper */
            $(this).css('padding', p.css('padding'));
            p.css('padding', '0 0');
        }
        $(this).tooltipster({
            delay: 0,
            speed: 0,
            touchDevices: false,
            arrow: false,
            position: "left",
            interactive: true,
            interactiveTolerance: 30,
            contentAsHTML: true,
            animation: 'fade',
            trigger: 'hover'
        });
    });
	
	$('table#itemstable').tablesorter();

})( jQuery, window, document );

function showHighligh() {
    $('tr.item').each(function () {
        var checkrow = $(this).attr('dritemid');

        if(rowsid.indexOf(checkrow) > -1) {
            //$(this).css('background', 'LightGray');
            $(this).addClass("selecteditem");
        } else {
            //$(this).css('background', '');
            $(this).removeClass("selecteditem");
        }

        if(hideid.indexOf(checkrow) > -1) {
            $(this).hide();
        }
    });
}

function ParseSame(list) {
    var result = {}, STR = "", i, j;
    for (i = 0; i < list.length; i++) {
        if (!result[list[i]]) {
            result[list[i]] = 1;
        } else {
            result[list[i]] = result[list[i]] + 1;
        }
    }
    for (j in result) {
        STR = STR + j + " x" + result[j] + "<br>";
    }
    STR = '<div class="color8" style="text-align: center;"><strong>' + list.length + ' item(s) selected.</strong></div><br />' + STR;
    return STR;
}

function MarkThem(){
    var counter = document.getElementById('massMark').value;
    if (counter < 1) {
        return false;
    }
    var marked = 0;
    $('tr.item').each(function () {
        var data;
        var rowid;
        var dropdata;
        var pushtodrop;
        if ($(this).hasClass("selecteditem") == false && marked < counter) {
            //change bg color
            //$(this).css('background', 'LightGray');
            $(this).addClass("selecteditem");

            // get tr attributes
            data = $(this).attr('drname');
            rowid = $(this).attr('dritemid');
            // push name to list
            show.push(data);
            rowsid.push(rowid);
            dropdata = {
                dropProfile: "",
                realm: $(this).attr('drrealm'),
                account: $(this).attr('draccount'),
                charName: $(this).attr('drchar'),
                itemType: $(this).attr('dritemtype'),
                dropit: $(this).attr('drmd5'),
                skin: $(this).attr('drImage'),
                itemID: $(this).attr('drID')
            };
            pushtodrop = JSON.stringify(dropdata);
            droparray.push(pushtodrop);
            marked++;
        }
    });
    var output = ParseSame(show);
    // update panel value
    document.getElementById('dropitem').value = JSON.stringify(droparray);
    $("#droplist").html(output);
    $("#tradelist").html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Cannot create trade list with mass mark function.</div>');
}

function ClearAll() {
    show 	= [];
    rowsid	= [];
    hideid	= [];
    droparray = [];
    document.getElementById('dropitem').value = "";
    document.getElementById('listinfo').value = "";
    $("#tradelist").html('<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Cleared!</div>');
    $("#droplist").html('<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Cleared!</div>');
    showHighligh();
}