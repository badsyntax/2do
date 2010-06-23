<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Task extends Controller_BaseAjax {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->request->response = '';
	}

	public function action_save(){

		if (isset($_POST['id'])) {

			$task = ORM::factory('task', (int) $_POST['id']);
			if (!$task->id) exit;
		} else {

			$task = ORM::factory('task');
			$task->sequence = 0;
			$task->list_id = (int) $_POST['list'];
			$task->user_id = $this->user->id;
		}

		$task->content = trim($_POST['task']);
		$task->save();

		$response = array(
			'outcome' => 'success',
			'message' => 'Successfully saved!',
			'id' => $task->id
		);

		$this->request->response = json_encode($response);
	}

	public function action_remove(){
		
		ORM::factory('task', (int) $_POST['id'])
			->where('user_id', '=', $this->user->id)
			->delete();

		$response = array(
			'outcome' => 'success',
			'message' => 'Successfully removed!'
		);
		
		$this->request->response = json_encode($response);
	}

	public function action_complete(){

		ORM::factory('task', (int) $_POST['id'])->complete();
		
		$response = array(
			'outcome' => 'success'
		);
		
		$this->request->response = json_encode($response);
	}

	public function action_incomplete(){
		
		$task = ORM::factory('task', (int) $_POST['id'])->incomplete();

		$response = array(
			'outcome' => 'success',
			'sequence' => $task->sequence
		);

		$this->request->response = json_encode($response);
	}

	public function action_reorder(){

		foreach($_POST['task'] as $i => $id){

			$task = ORM::factory('task')->where('id', '=', $id)->find();
			$task->sequence = $i;
			$task->save();
		}

		$task = ORM::factory('task', (int) $_POST['taskid']);
		$task->list_id = (int) $_POST['listid'];
		$task->save();
		
		$response = array(
			'outcome' => 'success'
		);
		
		$this->request->response = json_encode($response);
	}

}
