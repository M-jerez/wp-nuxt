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
        cursorBlink:true,
        cols: 2000,
        tabStopWidth:1
    });
    term.open(document.getElementById('x_terminal'));

    var finished = false;
    $.getJSON( "nuxt-generate.php?mode=runner", function( data ) {
        console.log(data);
    });

//    var running = setTimeout(function(){
//        if(!finished){
//            $.get( "nuxt-generate-progress.php", function( data ) {
//                var cols = maxColumns(data);
//
//
//                term.write(fixnewline(data));
//            });
//        }
//    },200);


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





