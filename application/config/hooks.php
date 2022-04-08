<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_controller'][] = array(
    'class'    => '',
    'function' => 'load',
    'filename' => 'exception_loader.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'load_config',
    'filename' => 'ipoints_config.php',
    'filepath' => 'hooks'
);
/*
Defining Maintenance Hook

To let the system know about the maintenance hook, edit the application/config/hooks.php file and define hook.

    pre_system – Hook point. The hook will be called very early during system execution.
    class – The name of the class wish to invoke.
    function – The method name wish to call.
    filename – The file name containing the class/function.
    filepath – The name of the directory containing hook script.
*/
$hook['pre_system'][] = array(
    'class'    => 'maintenance_hook',
    'function' => 'offline_check',
    'filename' => 'maintenance_hook.php',
    'filepath' => 'hooks'
);

/*
| Building an audit log in CodeIgniter
| Multiple Calls to the Same Hook

| If want to use the same hook point with more than one script, 
| simply make your array declaration multi-dimensional, like this:
| Notice the brackets after each array index:
*/
$hook['post_controller_constructor'][] = array(
    'class'    => 'Statistics',
    'function' => 'log_activity',
    'filename' => 'activity_tracking.php',
    'filepath' => 'hooks'
);