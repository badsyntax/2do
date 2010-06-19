<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Base {

	public function action_index(){

		if (Auth::instance()->logged_in()) {
			Request::instance()->redirect('lists');
		}

		$this->template->title = '2DO';

		$this->template->content = View::factory('page/home' );
	}

}
