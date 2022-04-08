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
		<link href="<?php echo base_url() . 'favicon-16x16.png'; ?>" rel="shortcut icon">
		<link href="<?php echo base_url() . 'apple-touch-icon.png'; ?>" rel="apple-touch-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/tether/dist/css/tether.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/bootstrap/dist/css/bootstrap.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/main.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/custom.css'; ?>"/>
         <?php if (!empty($headStyles)) {
            foreach ($headStyles as $style) {
                echo '<link type="text/css" rel="stylesheet" href="'.$style.'"/>'."\n";
            }
        }?>
        <script>var BASE_URL = "<?=base_url()?>"</script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/jquery/jquery-2.1.1.min.js'; ?>"></script>
        <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
        
        <?php if (!empty($footerScripts)) {
            foreach ($footerScripts as $script) {
                echo '<script type="text/javascript" src="'.$script.'"></script>'."\n";
            }
        }?>
    </head>
    <body>