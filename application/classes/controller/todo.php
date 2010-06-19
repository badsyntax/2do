<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Todo extends Controller_BaseAjax {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->request->response = '';
	}

	public function action_save(){
		
		ORM::factory('todo')
		->where('user_id', '=', $this->user->id)
		->shift_sequences(1);

		$todo = ORM::factory('todo');
		$todo->user_id = $this->user->id;
		$todo->sequence = 0;
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

		$todo = ORM::factory('todo', (int) $_POST['id']);

		if ($todo->user_id === Auth::instance()->get_user()->id) {

			$todo->delete();
		}

		ORM::factory('todo')
		->where('user_id', '=', $this->user->id)
		->shift_sequences(0);
		
		$response = array(
			'outcome' => 'success',
			'message' => 'Successfully removed!'
		);
		
		$this->request->response = json_encode($response);
	}

	public function action_done(){

		$todo = ORM::factory('todo', (int) $_POST['id']);
		$todo->done = TRUE;
		$todo->save();
		
		$response = array(
			'outcome' => 'success'
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
