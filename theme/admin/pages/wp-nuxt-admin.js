jQuery(function($) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    if(typeof IS_WPNUXT_ADMIN_PAGE !== 'undefined' && IS_WPNUXT_ADMIN_PAGE === true ){
        WPNUXT_ADMIN_PAGE($);
    }

});


/**
 * -----------------------------
 * ADMIN PANEL PAGE
 * -----------------------------
 * @param $
 * @constructor
 */
function WPNUXT_ADMIN_PAGE($){

    // --------- SAVE FUNCTIONALITY
    $("#wp-nuxt-save").click(function(){
        var button = $(this);
        var spinner = $(".save-action .spinner ");


        var settings = getFormValues();

        button.addClass("disabled");
        spinner.addClass("is-active");

        //save setting ajax call
        $.ajax({
            type: 'POST',
            cache: false,
            url: AJAX_URL+"?action="+AJAX_SAVE_ACTION,
            data: settings   // I WANT TO ADD EXTRA DATA + SERIALIZE DATA
        }).done(function(data){
            toastr.success('Settings saved');
        }).fail(function(jqXHR, textStatus, errorThrown){
            toastr.error('Problem saving setting, please contact your admin!<br><code>'+errorThrown+'</code>');
            wpnuxt_beep();
        }).always(function(){
            spinner.removeClass("is-active");
            button.removeClass("disabled");
        });



    });


    /**
     * Parses all inputs and return a serialized array including disabled and off checkboxes
     * @returns {*|jQuery}
     */
    function getFormValues(){
        //include disabled elemenets
        var disabled = $(".wp-nuxt-config-params").find(':input:disabled').prop('disabled',false);
        var settings = $(".wp-nuxt-config-params").serializeArray();


        //send unchecked checkboxed as off instead ommit them.
        $('.wp-nuxt-config-params').find('input[type=checkbox]:not(:checked)').each(function(){
            var el = $(this);
            settings.push({name:el.attr("name"),value:"off"});
        });

        //re-disable elements
        disabled.prop('disabled',true);

        return settings;
    }



    var node_path = "";
    var nuxt_path = "";
    var node_path_valid = false;
    var nuxt_path_valid = false;

    testNodePath();
    testNuxtPath();


    $("input[name='nuxt[node_path]']").blur(function(){
        testNodePath();
    });
    $("input[name='nuxt[nuxt_root_path]']").blur(function(){
        testNuxtPath();
    });


    /**
     * Calls to the server to test if the node.js path is valid
     */
    function testNodePath(){
        var el = $("input[name='nuxt[node_path]']");
        var container = el.parent();
        var val = el.val();
        if(!val){
            node_path_valid = false;
            pathFeedBack(container,false,"Empty Value");
            enableNuxtActions();
            return;
        }


        if(node_path !== val){
            node_path = val;
            $.ajax({
                type: 'POST',
                cache: false,
                url: AJAX_URL+"?action="+AJAX_NODE_PATH_ACTION,
                data: {path:node_path}
            }).done(function(data){
                var success = (data.status === "success");
                node_path_valid = success;
                pathFeedBack(container,success,data.message);
                enableNuxtActions();
            }).fail(function(jqXHR, textStatus, errorThrown){
                node_path_valid = false;
                toastr.error('Problem testing Node Path, please contact your admin!<br><code>'+errorThrown+'</code>');
                wpnuxt_beep();
            }).always(function(){

            });
        }
    }

    /**
     * Calls to the server to test if the Nuxt Root path is valid
     */
    function testNuxtPath(){
        var el = $("input[name='nuxt[nuxt_root_path]']");
        var container = el.parent();
        var val = el.val();
        if(!val){
            nuxt_path_valid = false;
            pathFeedBack(container,false,"Empty Value");
            enableNuxtActions();
            return;
        }


        if(nuxt_path !== val){
            nuxt_path = val;
            $.ajax({
                type: 'POST',
                cache: false,
                url: AJAX_URL+"?action="+AJAX_NUXT_PATH_ACTION,
                data: {path:nuxt_path}
            }).done(function(data){
                var success = (data.status === "success");
                nuxt_path_valid = success;
                pathFeedBack(container,success,data.message);
                enableNuxtActions();
            }).fail(function(jqXHR, textStatus, errorThrown){
                nuxt_path_valid = false;
                toastr.error('Problem testing nuxt Path, please contact your admin!<br><code>'+errorThrown+'</code>');
                wpnuxt_beep();
            }).always(function(){

            });
        }
    }


    /**
     * Prepends a label with a message to the container.
     * @param container
     * @param success
     * @param message
     */
    function pathFeedBack(container,success,message){
        container.find(".vld_message").remove();
        if(success){
            container.append("<span class='label label-success vld_message'>"+message+"</span>");
        }else{
            container.append("<span class='label label-error vld_message'>"+message+"</span>");
        }
    }


    /**
     * Enable-Disable the nuxt actions sections depending if node_path_valid and nuxt_path_valid are valid.
     */
    function enableNuxtActions(){
        if(node_path_valid && nuxt_path_valid){
            $("input[name='nuxt[automatic_generation]']").prop("disabled",false);
            $("#regenerate-site").prop("disabled",false);
            $("#nuxt-actions").css("opacity","1");
        }else{
            $("input[name='nuxt[automatic_generation]']").prop("disabled",true);
            $("#regenerate-site").prop("disabled",true);
            $("#nuxt-actions").css("opacity","0.4");
        }
    }


    //enable-disable the wordpress munu optins depending on the values of 'Hide Theme Setting' and 'Rest Menus'
    $("input[name='rest[menus]'] , input[name='wp_interface[disable_theme_settings]']").change(function(){
        var hide_theme = $("input[name='wp_interface[disable_theme_settings]']").is(":checked");
        var disable_users = $("input[name='rest[menus]'").is(":checked");

        if(hide_theme && disable_users){
            $("input[name='wp_interface[enable_menus]']").prop('checked', true);
        }else{
            $("input[name='wp_interface[enable_menus]']").prop('checked', false);
        }
    });
}


function wpnuxt_beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
    snd.play();
}


