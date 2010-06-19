<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Base {

	public function action_index(){

		$this->template->title = '2DO';

		$this->template->content = View::factory('page/home' );
	}

	public function action_save_user(){
		
		$person = ORM::factory('person');

		$person->name = 'Richard Willis';
		$person->email = 'willis.rh@gmail.com';
		$person->save();

		$this->request->response = 'Saved user';

	}

}
