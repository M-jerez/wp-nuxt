<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 06/03/2018
 * Time: 15:28
 */
?>
<div id="xterm_page_wrapper">
    <div class="xterm_page_header">
       <h4>WP Nuxt Console</h4> <button class="btn btn-action btn-sm circle open-close"><i class="dashicons dashicons-arrow-up-alt2 "></i></button>
    </div>
    <div class="xterm_page_content">
        <div id="x_terminal"></div>
    </div>
    <div class="xterm-running-icon">
        <span class="dashicons dashicons-image-rotate "></span>
    </div>
</div>


<?php global $themeURL; ?>
<script>
    var THEME_URL = "<?= $themeURL ?>";
    var API_URL = "<?= home_url() ?>/wp-json/wp/v2/";
    var AJAX_URL = "<?= admin_url('admin-ajax.php'); ?>";
    var AJAX_CMD_GENERATE = "wpnuxt-cmd-generate";
    var ADMIN_URL = "<?= site_url() ?>/wp-admin/";
    var IS_WPNUXT_TERM_PAGE = true;
</script>