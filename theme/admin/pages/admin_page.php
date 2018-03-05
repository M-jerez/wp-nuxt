<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 21/02/2018
 * Time: 14:33
 */

use wpnuxt\utils as utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$config = include(get_template_directory() ."/wp-nuxt-config.php");
?>
<div class="wrap wp-nuxt-wrap">
    <h1 class="wp-heading-inline"><?php p( "Wp Nuxt Config" ) ?></h1>

    <div id="post-body" class="metabox-holder columns-2">


        <form id="nuxt-config" class="postbox wp-nuxt-config-params">
            <h2 class="hndle"><span class="label label-primary"><?php p( "Nuxt" ) ?></span>
                <small><?php p( "Configures Nuxt.js Static Site Generator. <a href='https://nuxtjs.org/' target='_blank'>Nuxt.js documentation</a>" ) ?></small></h2>
            <div class="inside form-horizontal">


                <table class="table  ">
                    <thead>
                    <tr>
                        <th>Option</th>
                        <th width="300px">value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <strong><label for="nuxt[node_path]"><?php p( "Node.js Path" ) ?></label></strong><br>
                            <?php p( "The path in the system to the node.js binary o executable file." ); ?>
                        </td>
                        <td width="300px">
                            <input type="text" name="nuxt[node_path]" class="form-input" placeholder="/usr/local/bin/node" <?php echo utils::getConfigValueAttr($config["nuxt"]["node_path"]);?>>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong><label for="nuxt[nuxt_root_path]"><?php p( "Nuxt Path" ) ?>: </label></strong><br>
	                        <?php p( "The root directory of the Nuxt project, <code>nuxt generate</code> command will be executed from this directory." ) ?>
                            <br>
	                        <?php p( "This directory also contains the file <code>nuxt.config.js</code>" ) ?>
                        </td>
                        <td width="300px">
                            <input type="text" name="nuxt[nuxt_root_path]" class="form-input"
                                   placeholder="/var/www/vhosts/my_nuxt_site/" <?php echo utils::getConfigValueAttr($config["nuxt"]["nuxt_root_path"]);?>>
                            <br>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>&nbsp;<br>
                <table class="table" id="nuxt-actions">
                    <thead>
                    <tr>
                        <th>Option</th>
                        <th width="80px">Enabled</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <strong><?php p( "Automatic Static Site Generation" ) ?></strong><br>
				            <?php p( "When Enabled, the <code>nuxt generate</code> command will be executed after a post is created, updated or deleted." ) ?>
                        </td>
                        <td width="80px">
                            <label class="form-switch">
                                <input type="checkbox" name="nuxt[automatic_generation]" <?php echo utils::getConfigCheckedAttr($config["nuxt"]["automatic_generation"]);?> disabled>
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong><?php p( "Run <code>nux generate</code> command" ) ?></strong><br>
		                    <?php p( "Click on the button to run command and re-generate the static site" ) ?>
                        </td>
                        <td width="80px">
                            <button type="button" class="btn btn-primary btn-sm" id="regenerate-site" disabled >
                                <i class="dashicons dashicons-image-rotate icon"></i>
                                nuxt generate</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </form>


        <form id="rest-config" class="postbox  wp-nuxt-config-params">
            <h2 class="hndle"><span class="label label-primary"><?php p( "REST API" ) ?></span>
                <small><?php p( "Adds some extra functionality to the Wordpress REST API" ) ?></small></h2>
            <div class="inside form-horizontal">

                <table class="table  ">
                    <thead>
                    <tr>
                        <th>Option</th>
                        <th>Enpoint</th>
                        <th width="80px">Enabled</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
						<?php
                        $url = get_rest_url() . WPN_REST_URL . "/menus/" ;
						$url_id = get_rest_url() . WPN_REST_URL . "/menus/".htmlspecialchars('<id>');
                        ?>
                        <td><strong><?php p( "Add Menus Endpoint") ?></strong><br>
                            <?php p("This options adds an endpoint to list all WordPress Menus.") ?></td>
                        <td>
                            <a href="<?php echo $url ?>" target="_blank"><code><?php echo $url ?></code></a><br>
                            <a href="<?php echo $url_id ?>" target="_blank"><code><?php echo $url_id ?></code></a>
                        </td>
                        <td>
                            <label class="form-switch">
                                <input type="checkbox" name="rest[menus]" <?php echo utils::getConfigCheckedAttr($config["rest"]["menus"]);?>>
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    <tr>
						<?php $url = get_rest_url() . "wp/v2/users/" ?>
                        <td><strong><?php p( "Disable Users Endpoint")?></strong>
                            <br><small class="label"><?php p( "By default, WordPress allows to list all user accounts, which might be a security risk." ) ?></small>
                            <br><?php p("This option disables the endpoint to list all user as this might be a security risk.");?>
                        </td>
                        <td><a href="<?php echo $url ?>" target="_blank"><code><?php echo $url ?></code></a></td>
                        <td>
                            <label class="form-switch">
                                <input type="checkbox" name="rest[disable_users]" <?php echo utils::getConfigCheckedAttr($config["rest"]["disable_users"]);?>>
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>&nbsp;<br>
                <table class="table  ">
                    <thead>
                    <tr>
                        <th>Option</th>
                        <th width="80px">Enabled</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <strong><?php p( "Cache REST API" ) ?></strong><br>
	                        <?php p( "When Enabled, all REST API endpoints will be cached, the cache is completely cleared after a post is modified." ); ?>
                            <br><code>//TODO : this functionality is not yet implemented</code>
                        </td>
                        <td width="80px">
                            <label class="form-switch">
                                <input type="checkbox" name="rest[cache]" <?php echo utils::getConfigCheckedAttr($config["rest"]["cache"]);?> disabled>
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </form>



        <form id="wp-interface-config" class="postbox wp-nuxt-config-params">
            <h2 class="hndle"><span class="label label-primary"><?php p( "WordPress Interface" ) ?></span>
                <small><?php p( "Extra options to configure the WordPress interface." ) ?></small>
            </h2>
            <div class="inside form-horizontal">

                <table class="table  ">
                    <thead>
                    <tr>
                        <th>Option</th>
                        <th width="80px">Enabled</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong><?php p("Hide Theme Settings") ?></strong>
                            <br><?php p("As all the theme functionality is disabled, this option also hides the 'Theme Settings' tab fom the WordPress menu.") ?>
                        </td>
                        <td>
                            <label class="form-switch">
                                <input type="checkbox" name="wp_interface[disable_theme_settings]" <?php echo utils::getConfigCheckedAttr($config["wp_interface"]["disable_theme_settings"]);?>>
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    <tr id="menus-page">
                        <td><strong><?php p( "Show WP Menus Tab")?></strong><br>
	                        <?php p( "This option is automatically enabled when <code>Hide Theme Settings</code> and the <code>Add Menus Endpoint</code> options are selected.")?>
                            <br>
                        </td>
                        <td>
                            <label class="form-switch">
                                <input type="checkbox" name="wp_interface[enable_menus]" <?php echo utils::getConfigCheckedAttr($config["wp_interface"]["enable_menus"]);?> disabled>
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="info">
                <p>
	                <?php p( "Please refresh the page to see changes after save this settings." ) ?>
                </p>
                <span class="dashicons dashicons-info"></span>
            </div>
        </form>

        <div class="save-action">
            <a class="button button-primary button-large" href="#" id="wp-nuxt-save">Save</a>
            <span class="spinner"></span>
        </div>
    </div>
</div>
<?php global $nonce_name, $themeURL; ?>
<script>
    var THEME_URL = "<?= $themeURL ?>";
    var API_URL = "<?= home_url() ?>/wp-json/wp/v2/";
    var AJAX_URL = "<?= admin_url('admin-ajax.php'); ?>";
    var AJAX_SAVE_ACTION = "save-<?= $nonce_name ?>";
    var AJAX_NODE_PATH_ACTION = "test-node-path";
    var AJAX_NUXT_PATH_ACTION = "test-nuxt-path";
    var ADMIN_URL = "<?= site_url() ?>/wp-admin/";
</script>