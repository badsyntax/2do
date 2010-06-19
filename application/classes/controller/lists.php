<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Lists extends Controller_Base {

	public $auth_required = array('login');

	function action_index(){

		//$person_username = Request::instance()->param('username');
		
		$this->template->title = 'Lists';

		$lists = View::factory('page/lists');
		$lists->lists = ORM::factory('todo')
			->where('user_id', '=', $this->user->id)
			->order_by('sequence', 'ASC')
			->find_all();

		$this->template->content = $lists;
	}

}
