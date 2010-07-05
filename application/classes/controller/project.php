<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Project extends Controller_BaseAjax {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->request->response = '';
	}

	public function action_save(){

		if (isset($_POST['id'])) {

			$task = ORM::factory('project', (int) $_POST['id']);

			if (!$task->id) exit;
		} else {

			$project = ORM::factory('project');
			$project->user_id = $this->user->id;
		}
		
		$name = trim($_POST['name']);

		if ($name) {
			$project->name = $name;
		}

		$project->save();

		$this->request->response = json_encode(
		array(
			'outcome' => 'success',
			'message' => 'Successfully saved!',
			'id' => $project->id
		));
	}
}
