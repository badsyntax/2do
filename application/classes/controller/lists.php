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

	function action_newtodo(){

		echo new View( $this->mobile ? 'page/units/newtodo_mobile' : 'page/units/newtodo' );
		exit;
	}

}
