<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 06/03/2018
 * Time: 15:28
 */
?>



<?php global $nonce_name, $themeURL; ?>
<script>
    var THEME_URL = "<?= $themeURL ?>";
    var API_URL = "<?= home_url() ?>/wp-json/wp/v2/";
    var AJAX_URL = "<?= admin_url('admin-ajax.php'); ?>";
    var AJAX_START_TERM = "start-term-worker";
    var AJAX_STOP_TERM = "stop-term-worker";
    var ADMIN_URL = "<?= site_url() ?>/wp-admin/";
    var IS_WPNUXT_TERM_PAGE = true;
</script>