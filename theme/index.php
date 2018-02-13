<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 12/02/2018
 * Time: 17:24
 */

header( "HTTP/1.0 405 Forbidden" );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title> WP-NUXT | The theme functionality is disabled on this site </title>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <style>
        body,html{
            background-color: #FFF;
            font-family: Lato, sans-serif;
            height: 100%;
            width: 100%;
        }
        .logo{
            display: block;
            max-width: 100%;
            height: auto;
        }
        #content{
            text-align: center;
            width: auto;
            height: 100vh;
        }
        .container{
            max-width: 600px;
            margin: auto;
        }
        .page_title{
            font-size: 1.8rem;
            font-weight: lighter;
            margin: 0;
        }
        .valign-wrapper {
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;

            -ms-flex-align: center;
            -webkit-align-items: center;
            -webkit-box-align: center;

            align-items: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="valign-wrapper" id="content">
        <div>
            <h4 class="page_title">Theme functionality is disabled on this site!</h4>
            <img src="<?php echo get_template_directory_uri();?>/screenshot.png" alt="wp-nuxt" class="logo">
        </div>
    </div>
</div>
</body>
</html>
