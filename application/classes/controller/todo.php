<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Todo extends Controller_BaseAjax {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->request->response = 'todo ajax!';
	}

	public function action_save(){

		$todo = ORM::factory('todo');
		$todo->user_id = $this->user->id;
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
		
		$response = array(
			'outcome' => 'success',
			'message' => 'Successfully removed!'
		);
		
		$this->request->response = json_encode($response);
	}

}
