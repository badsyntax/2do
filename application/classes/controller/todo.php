<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Todo extends Controller_BaseAjax {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->request->response = '';
	}

	public function action_save(){

		if (isset($_POST['id'])) {

			$todo = ORM::factory('todo', (int) $_POST['id']);
			if (!$todo->id) exit;
		} else {

			$todo = ORM::factory('todo');
			$todo->sequence = 0;
			$todo->list_id = (int) $_POST['list'];
			$todo->user_id = $this->user->id;
		}

		$todo->content = trim($_POST['todo']);
		$todo->save();

		$response = array(
			'outcome' => 'success',
			'message' => 'Successfully saved!',
			'id' => $todo->id
		);

		$this->request->response = json_encode($response);
	}

	public function action_remove(){
		
		ORM::factory('todo', (int) $_POST['id'])
			->where('user_id', '=', $this->user->id)
			->delete();

		$response = array(
			'outcome' => 'success',
			'message' => 'Successfully removed!'
		);
		
		$this->request->response = json_encode($response);
	}

	public function action_complete(){

		ORM::factory('todo', (int) $_POST['id'])->complete();
		
		$response = array(
			'outcome' => 'success'
		);
		
		$this->request->response = json_encode($response);
	}

	public function action_incomplete(){
		
		$todo = ORM::factory('todo', (int) $_POST['id'])->incomplete();

		$response = array(
			'outcome' => 'success',
			'sequence' => $todo->sequence
		);

		$this->request->response = json_encode($response);
	}

	public function action_reorder(){

		foreach($_POST['todo'] as $i => $id){

			$todo = ORM::factory('todo')->where('id', '=', $id)->find();
			$todo->sequence = $i;
			$todo->save();
		}
		
		$response = array(
			'outcome' => 'success'
		);
		
		$this->request->response = json_encode($response);
	}

}
