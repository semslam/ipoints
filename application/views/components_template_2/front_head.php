<!DOCTYPE html>
<html class="app">
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="utf-8">
		<meta content="ie=edge" http-equiv="x-ua-compatible">
		<meta content="template language" name="keywords">
		<meta content="John Doe" name="author">
		<meta content="Admin Template" name="description">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<link href="<?php echo base_url() . 'favicon.png'; ?>" rel="shortcut icon">
		<link href="<?php echo base_url() . 'apple-touch-icon.png'; ?>" rel="apple-touch-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <link rel="stylesheet" href="<?php echo base_url() . 'assets/template_2/css/bootstrap.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/font-awesome/css/font-awesome.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/animate/animate.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/template_2/css/album.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/sweetalert/dist/sweetalert.css'; ?>"/>
            <?php if (!empty($headStyles)) {
                foreach ($headStyles as $style) {
                    echo '<link type="text/css" rel="stylesheet" href="'.$style.'"/>'."\n";
                }
            }?>
		 <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/jquery/jquery-2.1.1.min.js'; ?>"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
       <script type="text/javascript" src="<?php echo base_url() . 'assets/js/front.js'; ?>"></script>
       <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/sweetalert/dist/sweetalert.min.js'; ?>"></script>
	   <?php if (!empty($footerScripts)) {
            foreach ($footerScripts as $script) {
                echo '<script type="text/javascript" src="'.$script.'"></script>'.PHP_EOL;
            }
        }?>
	   
	    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="assets/template_2/js/vendor/jquery-slim.min.js"><\/script>')</script> -->
    <!-- <script src="<?php echo base_url() . 'assets/template_2/js/holder.min.js'; ?>"></script> -->
    </head>
    <body>