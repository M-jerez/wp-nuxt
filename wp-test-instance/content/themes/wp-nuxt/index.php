<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 12/02/2018
 * Time: 17:24
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title> WP-NUXT | The theme functionality is disabled on this site </title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <style>
        body,html{
            background-color: #FFF;
            f
            ont-family: Lato, sans-serif;
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
        .valign-wrapper
    </style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col s12">
			<div class="valign-wrapper" id="content">
                <div>
                    <h4>Theme functionality is disabled on this site!</h4>
                    <img src="<?php echo get_template_directory_uri();?>/screenshot.png" alt="wp-nuxt" class="logo">
                </div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
