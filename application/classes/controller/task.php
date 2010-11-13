<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Task extends Controller_BaseAjax {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->request->response = '';
	}

	public function action_view($id=0){

		$task_template = View::factory( $this->mobile ? 'page/task_view_mobile' : 'page/task_view' );
		$task_template->task = ORM::factory('task', (int) $id);

		$this->template->content = $task_template;
	}

	public function action_new(){

		echo new View( $this->mobile ? 'page/task_new_mobile' : 'page/task_new' );
		exit;
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

		$cache_key = ( $this->user ? $this->user->id : 0 );
		Cache::instance()->delete($cache_key);

		if ($this->mobile){

			echo 'test';
		} else {

			$this->request->response = json_encode(
			array(
				'status' => 'success',
				'message' => 'Successfully saved!',
				'id' => $task->id
			));
		}
	}

	public function action_time(){

		$response = array();

		$task = ORM::factory('task');

		if ($task->values($_POST)->check()) {

			$response['status'] = 'success';

			$task->save();

		} else {

			$response['status'] = 'error';

			$response = array_merge(
				array('errors' => $task->validate()->errors('tasktime')),
				$response
			);
		}

		$this->request->response = json_encode($response);
	}

	public function action_remove(){
		
		ORM::factory('task', (int) $_POST['id'])
			->where('user_id', '=', $this->user->id)
			->delete();

		$this->request->response = json_encode(
		array(
			'status' => 'success',
			'message' => 'Successfully removed!'
		));
	}

	public function action_complete(){

		ORM::factory('task', (int) $_POST['id'])->complete();
		
		$this->request->response = json_encode(
		array(
			'status' => 'success'
		));
	}

	public function action_incomplete(){
		
		$task = ORM::factory('task', (int) $_POST['id'])->incomplete();

		$this->request->response = json_encode(
		array(
			'status' => 'success',
			'sequence' => $task->sequence
		));
	}

	public function action_reorder(){

		foreach($_POST['task'] as $i => $id){

			$task = ORM::factory('task', (int) $id)->find();
			$task->sequence = $i;
			$task->save();
		}

		$this->request->response = json_encode(
		array(
			'status' => 'success'
		));
	}

}
