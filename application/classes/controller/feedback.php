<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Feedback extends Controller_Base {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->template->title = '2do : feedback';

		$this->template->content = View::factory('page/feedback' );
	}
}
