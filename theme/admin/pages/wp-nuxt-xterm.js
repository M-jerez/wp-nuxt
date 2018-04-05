jQuery(function ($) {
    if (typeof IS_WPNUXT_TERM_PAGE !== 'undefined' && IS_WPNUXT_TERM_PAGE === true) {
        WPNUXT_TERM_PAGE($);
    }
});


/**
 * -----------------------------
 * X TERMINAL SECTION
 * -----------------------------
 * @param $
 * @constructor
 */
function WPNUXT_TERM_PAGE($) {
    var $xterm_wrapper = $("#xterm_page_wrapper");


    var term = new Terminal({
        cursorBlink: false,
        cols: 2000,
        tabStopWidth: 1
    });
    term.open(document.getElementById('x_terminal'));
    term.fit();


    enableTopBarButton();


    $(".wp-nuxt-generate-site").click(generateCMD);


    $(".xterm_page_header .open-close").click(function () {
        $xterm_wrapper.toggleClass("active");
    });


    //AJAX_URL = "/content/themes/wp-nuxt/modules/run_cmd/non_wp.php";

    var generatingCMD = false;
    function generateCMD() {
        if(generatingCMD){
            return;
        }
        generatingCMD = true;
        $xterm_wrapper.addClass("active");
        term.clear();
        animateRunning();
        $.ajax({
            type: 'GET',
            cache: false,
            url: AJAX_URL + "?action=" + AJAX_CMD_GENERATE + "&mode=runner"
        }).done(function (data) {
            //TODO: finsih
            console.log("runner");
            console.log(data);
            generatingCMD = false;
            var success = (data.status === "success");
            if (success) {

            } else {
                xtermError(data.message);
                if (typeof data.data.output[0] !== 'undefined') {
                    xtermError(data.data.output[0].content);
                }
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            //TODO
        }).always(function () {
            //TODO
            stopAnimateRunning();
            generatingCMD = false;
        });

        readCMD(0);
    }




    function readCMD(start_line) {
        if (!generatingCMD){
            return;
        }

        setTimeout(function () {
            $.ajax({
                type: 'GET',
                cache: false,
                url: AJAX_URL + "?action=" + AJAX_CMD_GENERATE + "&mode=reader",
                data:{
                    line_number : start_line
                }
            }).done(function (data) {
                //TODO: finish
                printOrdeter();
                console.log("reader");
                console.log(data);
                var success = (data.status === "success");
                if (success) {

                } else {
                    xtermError(data.message);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
            }).always(function () {
                readCMD(start_line + 1);
            });
        }, 300);
    }

    var orderedresponses = {};
    function printOrdeter(linenum){

    }


    function animateRunning() {
        $(".xterm-running-icon > .dashicons-image-rotate").addClass("rotate_anim");
    }

    function stopAnimateRunning() {
        $(".xterm-running-icon > .dashicons-image-rotate").removeClass("rotate_anim");
    }


    function xtermError($message) {
        term.write("\u001b[41m\u001b[39m ERROR: " + fixnewline($message) + " \u001b[39m\u001b[49m\r\n");
    }


    function fixnewline(text) {
        return text.replace(/\r?\n/g, "\r\n");
    }

    function maxColumns(text) {
        var r = text.split("\n");

        var max = 0;
        for (var i = 0; i < r.length; i++) {
            var line = r[i];
            max = Math.max(line.length, max);
        }
        return max;
    }


    function enableTopBarButton() {
        var li = $("<li class='wpnux-top-bar-li' id='wpnuxt-gen-top-bar'><button type=\"button\" " +
            "class=\"btn btn-primary btn-sm wp-nuxt-generate-site\" id=\"regenerate-site-top-bar\"  disabled>\n" +
            "    <i class=\"dashicons dashicons-image-rotate icon\"></i>\n" +
            "    nuxt generate</button></li>");
        $("#wp-admin-bar-top-secondary").append(li);

        li.click(generateCMD);
    }
}





