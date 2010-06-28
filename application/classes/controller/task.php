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
			$task->user_id = $this->user->id;
		}
		
		$content = trim($_POST['task']);

		if ($content) {
			$task->content = $content;
		}

		$task->list_id = (int) $_POST['list'];
		$task->save();

		$this->request->response = json_encode(
		array(
			'outcome' => 'success',
			'message' => 'Successfully saved!',
			'id' => $task->id
		));
	}

	public function action_remove(){
		
		ORM::factory('task', (int) $_POST['id'])
			->where('user_id', '=', $this->user->id)
			->delete();

		$this->request->response = json_encode(
		array(
			'outcome' => 'success',
			'message' => 'Successfully removed!'
		));
	}

	public function action_complete(){

		ORM::factory('task', (int) $_POST['id'])->complete();
		
		$this->request->response = json_encode(
		array(
			'outcome' => 'success'
		));
	}

	public function action_incomplete(){
		
		$task = ORM::factory('task', (int) $_POST['id'])->incomplete();

		$this->request->response = json_encode(
		array(
			'outcome' => 'success',
			'sequence' => $task->sequence
		));
	}

	public function action_reorder(){

		foreach($_POST['task'] as $i => $id){

			$task = ORM::factory('task')->where('id', '=', $id)->find();
			$task->sequence = $i;
			$task->save();
		}

		$this->request->response = json_encode(
		array(
			'outcome' => 'success'
		));
	}

}
