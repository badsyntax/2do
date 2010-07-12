<?php defined('SYSPATH') or die('No direct script access.');

class Controller_404 extends Controller_Base {

	public function action_index(){

		$this->template->title = '404 : page not found';

		$this->template->content = View::factory('page/errors/404' );
	}

}
