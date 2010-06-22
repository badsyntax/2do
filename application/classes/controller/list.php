<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_List extends Controller_Base {

	public $auth_required = array('login');

	function action_index(){

		$today = date('d/m/Y');

		$date = $this->request->param('date');

		if (!$date) {

			$date = $today;
		}

		$this->template->title ='2do';

		$lists_template = View::factory('page/lists');

		$lists = array();
		
		foreach($l = ORM::factory('list')->find_all() as $list){

			$data = array(
				'list' => $list,
				'tasks' => $list->get_tasks($this->user->id, $date)
			);

			array_push($lists, $data);
		}
		
		$lists_template->lists = $lists;
		$lists_template->complete = ORM::factory('task')->get_completed($this->user->id, $date);

		$this->template->content = $lists_template;
	}

}
