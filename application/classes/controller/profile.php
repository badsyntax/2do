<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Profile extends Controller_Base {
 
	function action_index(){

		$this->template->title = 'Manage profile';		

		$this->template->content = 'Edit profile';	
	}
 
}
