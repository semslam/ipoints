<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Creating Siteconfig model
| -------------------------------------------------------------------------
| This file lets you update configuration into database 
| get_all() function is used in our my_config hooks file to grab all data from our database.
| update_config() function is used to update our config data when the form is submitted from config view file.
| save() function is called form the update_config() function to update one by one.
|
*/
class Siteconfig extends CI_Model {

 public function __construct()
 {
  parent::__construct();
 }
 public function get_all()
 {
  return $this->db->get('settings');
 }
 public function update_config($data)
 {
  $success = true;
  foreach($data as $key=>$value)
  {
   if(!$this->save($key,$value))
   {
    $success=false;
    break;  
   }
  }
  return $success;
 }
 public function save($key,$value)
 {
  $config_data=array(
    'key'=>$key,
    'value'=>$value
    );
  $this->db->where('key', $key);
  return $this->db->update('settings',$config_data); 
 }
}