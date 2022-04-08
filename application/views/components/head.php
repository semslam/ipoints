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
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/font-awesome/css/font-awesome.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/animate/animate.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/datatable/media/css/jquery.dataTables.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/datatable/media/css/dataTables.bootstrap4.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/dropzone/dist/dropzone.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/select2/dist/css/select2.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/bootstrap-daterangepicker/daterangepicker.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/fancybox/dist/jquery.fancybox.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/sweetalert/dist/sweetalert.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/exort/uploader.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/jquery-treegrid/jquery.treegrid.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/main.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/custom.css'; ?>"/>
		 <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
         <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/datatables.min.css'; ?>"/>
         <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/generic.css'; ?>"/>
         <?php if (!empty($headStyles)) {
            foreach ($headStyles as $style) {
                echo '<link type="text/css" rel="stylesheet" href="'.$style.'"/>'."\n";
            }
        }?>
        <script>var BASE_URL = "<?=base_url()?>"</script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/jquery/jquery-2.1.1.min.js'; ?>"></script>
        <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/jquery-count-to/jquery.countTo.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/moment/min/moment.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/chart.js/dist/Chart.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/datatable/media/js/jquery.dataTables.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/datatable/media/js/dataTables.bootstrap4.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/select2/dist/js/select2.full.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/bootstrap-daterangepicker/daterangepicker.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/bootstrap-validator/dist/validator.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/fancybox/dist/jquery.fancybox.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/disabler-enabler/disabler.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/disabler-enabler/enabler.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/sweetalert/dist/sweetalert.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/super-datagrid/datagrid.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/exort/uploader.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/jquery-treegrid/jquery.treegrid.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/slimscroll/jquery.slimscroll.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/tether/dist/js/tether.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/bootstrap/dist/js/bootstrap.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/bootstrap/dist/js/bootstrap3-typeahead.min.js'; ?>"></script>
        <script type="text/javascript" >var BASE_URL = "<?php echo base_url()?>"</script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/generic-lib.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/app.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/datatables.min.js'; ?>"></script>
        
        <?php if (!empty($footerScripts)) {
            foreach ($footerScripts as $script) {
                echo '<script type="text/javascript" src="'.$script.'"></script>'."\n";
            }
        }?>
    </head>
    <body>