<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_List extends Controller_Base {

	public $auth_required = array('login');

	function action_index(){

		$person_username = Request::instance()->param('username');

		$this->template->title = 'Lists';
		$this->template->content = 'show list for '.$person_username;	
	}

}
