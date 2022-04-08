<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuHandler 
{
    /**
     * Get Active Menu
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

  public static function active_menu($list_menu)
	{
		$active_menu = 0;
		$ci =& get_instance();
		foreach ($list_menu as $menu) {
			if ($ci->uri->segment(1) == $menu->link || $ci->uri->segment(1) . '/' . $ci->uri->segment(2) == $menu->link) {
				if ($menu->parent_id != 0 && $menu->is_have_child == 0) {
					$active_menu = $menu->parent_id;
				} else if ($menu->parent_id == 0 && $menu->is_have_child == 0) {
					$active_menu = $menu->id;
				}
			}
		}
		return $active_menu;
	}
}
