<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin extends Controller_Base {
 
	function action_index(){
		$this->template->content = 'admin';	
	}
 
 
} // End of ./application/controller/admin.php
