<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_List extends Controller_Base {

	public $auth_required = array('login');

	function action_index(){

		$date = $this->request->param('date');
		
		if (!$date) {

			$date = date('d/m/Y');
		}

		$this->template->title ='2do';

		$lists_template = View::factory('page/lists');

		$lists = array();
		
		foreach($l = ORM::factory('list')->find_all() as $i => $list){

			$data = array(
				'list' => $list,
				'tasks' => $list->get_tasks($this->user->id, $date)
			);

			array_push($lists, $data);
		}
		
		$lists_template->lists = $lists;
		$lists_template->hidden_lists = explode(',', @$_COOKIE['hiddenlists']);
		$lists_template->complete = ORM::factory('task')->get_completed($this->user->id, $date);

		$this->template->content = $lists_template;
	}

}
