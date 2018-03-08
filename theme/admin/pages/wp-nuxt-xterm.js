jQuery(function($) {
    if(typeof IS_WPNUXT_TERM_PAGE !== 'undefined' && IS_WPNUXT_TERM_PAGE === true ){
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
function WPNUXT_TERM_PAGE($){
     var $xterm_wrapper = $("#xterm_page_wrapper");




     $(".xterm_page_header .open-close").click(function(){
         $xterm_wrapper.toggleClass("active");
     });

    var term = new Terminal({
        cursorBlink:false,
        cols: 2000,
        tabStopWidth:1
    });
    term.open(document.getElementById('x_terminal'));
    term.fit();




    $(".wp-nuxt-generate-site").click(function(){



        //save setting ajax call

        generateCMD();

    });



    function generateCMD(){
        $xterm_wrapper.addClass("active");
        term.clear();
        animateRunning();
        $.ajax({
            type: 'GET',
            cache: false,
            url: AJAX_URL+"?action="+AJAX_CMD_GENERATE+"&mode=runner"
        }).done(function(data){
            //TODO: finsih
            console.log(data);
            var success = (data.status === "success");
            if(success){

            }else{
                xtermError(data.message);
                if(typeof data.data.output[0] !== 'undefined'){
                    xtermError(data.data.output[0].content);
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            //TODO
        }).always(function(){
            //TODO
            stopAnimateRunning();
        });
    }


    var readCMDFailed = false;

    function readCMD(){
        if(readCMDFailed)
            return;
        setTimeout(function(){
            $.ajax({
                type: 'GET',
                cache: false,
                url: AJAX_URL+"?action="+AJAX_CMD_GENERATE+"&mode=reader"
            }).done(function(data){
                //TODO: finsih

                if(!finished){
                    readCMD()
                }else{

                }
                console.log(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                //TODO
                readCMDFailed = true;
            }).always(function(){
                //TODO
            });
        },400);
    }


    function animateRunning(){
        $(".xterm-running-icon > .dashicons-image-rotate").addClass("rotate_anim");
    }

    function stopAnimateRunning(){
        $(".xterm-running-icon > .dashicons-image-rotate").removeClass("rotate_anim");
    }



    function xtermError($message){
        term.write("\u001b[41m\u001b[39m ERROR: "+fixnewline($message)+" \u001b[39m\u001b[49m\r\n");
    }



    function fixnewline(text){
        return text.replace(/\r?\n/g, "\r\n");
    }

    function maxColumns(text){
        var r = text.split("\n");

        var max =0;
        for(var i=0;i<r.length;i++){
            var line = r[i];
            max = Math.max(line.length,max);
        }
        return max;
    }
}





