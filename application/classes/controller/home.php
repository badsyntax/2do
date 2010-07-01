<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Base {

	public function action_index(){

		if (Auth::instance()->logged_in()) {

			$this->template = Request::factory('list')->execute()->response;
		} else {

			$this->template = Request::factory('sign-in')->execute()->response;
		}
	}

}
