<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you Load configuration from database into global CI config
| files.  Please see the user guide for info:
| We grab the config data from our database by using Siteconfig model. 
| And set the data to global CI config. So we need to create Siteconfig model in our model folder. 
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
  
  function load_config()
  {
   $CI =& get_instance();
   foreach($CI->Siteconfig->get_all()->result() as $site_config)
   {
    $CI->config->set_item($site_config->meta_key,$site_config->meta_value);
   }
  }
