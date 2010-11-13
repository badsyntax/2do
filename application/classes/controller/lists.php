<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Lists extends Controller_Base {

	public $auth_required = array('login');

	function action_index(){

		$date = $this->request->param('date');

		if (!$date) {

			$date = date('d/m/Y');
		}

		$this->template->title ='2do';

		$lists_template = View::factory( $this->mobile ? 'page/lists_mobile' : 'page/lists' );

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
		
		$this->template->set_global('projects', ORM::factory('project')->where('user_id', '=', $this->user->id)->find_all());

		$this->template->content = $lists_template;
	}

	function action_view($id=0){

		$this->template->title ='2do';

		$lists_template = View::factory( $this->mobile ? 'page/lists_view_mobile' : 'page/lists_view' );
		
		$date = date('d/m/Y');

		$lists_template->list = ORM::factory('list')->find($id);
		$lists_template->tasks = $lists_template->list->get_tasks($this->user->id, $date);
		$lists_template->hidden_lists = explode(',', @$_COOKIE['hiddenlists']);
		$lists_template->complete = ORM::factory('task')->get_completed($this->user->id, $date);
		
		$this->template->set_global('projects', ORM::factory('project')->where('user_id', '=', $this->user->id)->find_all());
		
		$this->template->content = $lists_template;
	}

}
