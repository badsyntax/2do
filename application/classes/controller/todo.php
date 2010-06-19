<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Todo extends Controller_BaseAjax {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->request->response = 'todo ajax!';
	}

	public function action_save(){

		$response = array(
			'outcome' => 'success',
			'message' => 'Successfully saved!'
		);

		$this->request->response = json_encode($response);
	}

}
