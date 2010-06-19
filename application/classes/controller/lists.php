<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Lists extends Controller_Base {

	public $auth_required = array('login');

	function action_index(){

		$person_username = Request::instance()->param('username');
		
		$this->template->user = $user = Session::instance()->get('auth_user');

		$this->template->title = 'Lists';
		$this->template->content = View::factory('page/lists');
	}

}
