<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Errors extends Controller_Base {

	public function action_404(){

		$this->template->title = '404';

		$this->template->content = View::factory('page/home' );
	}

}
