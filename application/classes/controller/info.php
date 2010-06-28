<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Info extends Controller_Base {

	public function action_index(){

		$this->template->title = '2do : info';

		$this->template->content = View::factory('page/info' );
	}

}
