<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 21/02/2018
 * Time: 14:33
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php p( "Wp Nuxt Config" ) ?></h1>

    <div id="post-body" class="metabox-holder columns-2">


        <div id="nuxt-config" class="postbox">
            <h2 class="hndle"><span><?php p( "Nuxt" ) ?></span></h2>
            <div class="inside form-horizontal">
                <div class="form-group">
                    <div class="col-3 col-sm-12">
                        <label for="node-path"><?php p( "Node.js path" ) ?></label>
                    </div>
                    <div class="col-9 col-sm-12">
                        <input type="text" name="node-path" class="form-input" placeholder="/usr/local/lib/node">
                        <sub><?php p( "The path in the system to the node.js binary o executable file." ); ?></sub>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-3 col-sm-12">
                        <label for="nux-path"><?php p( "Nuxt root path" ) ?>: </label>
                    </div>
                    <div class="col-9 col-sm-12">
                        <input type="text" name="node-path" class="form-input"
                               placeholder="/var/www/vhosts/my_nuxt_site/">
                        <sub>
							<?php p( "The root directory of the Nuxt project, <code>nuxt generate</code> command will be executed from this directory." ) ?>
							<?php p( "This directory also contains the file <code>nuxt.config.js</code>" ) ?>
                        </sub>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="form-switch">
                            <input type="checkbox">
                            <i class="form-icon"></i><?php p( "Automatic Static Site Generation" ) ?>
                        </label>
                        <br>
                        <sub><?php p( "When Enabled, the <code>nuxt generate</code> command will be executed after a post is created, updated or deleted." ) ?></sub>
                    </div>
                </div>

            </div>

            <div class="info">
                <p>
					<?php p( "Configures Nuxt.js Static Site Generator: for more info about Nuxt.js please visit their <a href='https://nuxtjs.org/' target='_blank'>documentation.</a>" ) ?>
                </p>
                <span class="dashicons dashicons-info"></span>
            </div>
        </div>


        <div id="nuxt-config" class="postbox">
            <h2 class="hndle"><span><?php p( "Rest" ) ?></span></h2>
            <div class="inside form-horizontal">

                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Enpoint</th>
                        <th>Description</th>
                        <th>Enabled</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
						<?php $url = get_rest_url() . NUXT_PRESS_REST_NAME . "/menus" ?>
                        <td><a href="<?php echo $url ?>" target="_blank"><code><?php echo $url ?></code></a></td>
                        <td><?php p( "List All WP Menus on the Rest API") ?><br><?php p("This Options Also Enables the WP Menus page on the Admin panel.") ?></td>
                        <td>
                            <label class="form-switch">
                                <input type="checkbox">
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    <tr>
						<?php $url = get_rest_url() . "wp/v2/users/" ?>
                        <td><a href="<?php echo $url ?>" target="_blank"><code><?php echo $url ?></code></a></td>
                        <td><span class="label label-error"><?php p( "Disable the endpoint to List all WP users (better security)")?></span>
                        </td>
                        <td>
                            <label class="form-switch">
                                <input type="checkbox">
                                <i class="form-icon"></i>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="form-switch">
                            <input type="checkbox">
                            <i class="form-icon"></i> <?php p( "Cache REST API Endpoints" ) ?>
                        </label>
                        <br>
                        <sub><?php p( "When Enabled, all REST API endpoints will be cached, the cache is completely cleared after a post is modified" ); ?></sub>
                    </div>
                </div>

            </div>

            <div class="info">
                <p>
					<?php p( "Adds some extra functionality to the Wordpress REST API, like new endpoints and caching." ) ?>
                    <br><?php p( "Also disables the existing User Endpoint as a security measure." ) ?>
                    <small class="label"><?php p( "By default, WordPress allows to list all user accounts, which might be a security risk." ) ?></small>
                </p>
                <span class="dashicons dashicons-info"></span>
            </div>
        </div>

        <div class="save-action">
            <span class="spinner is-active"></span>
            <button class="button button-primary button-large" id="node-save">Save</button>
        </div>
    </div>
</div>