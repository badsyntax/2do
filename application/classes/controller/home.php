<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Base {

	public function action_index(){

		$this->template->title = '2do : organize your life';

		if (Auth::instance()->logged_in()) {

			$this->template = Request::factory('lists')->execute()->response;

		} else {

			$this->template->content = View::factory('page/home_signin');
		}
	}

}
