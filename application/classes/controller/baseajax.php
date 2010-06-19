<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_BaseAjax extends Controller {
  
	public $auth_required = array('login');

	public $secure_actions = FALSE;
  
	public function before() {

		parent::before();

		$this->session = Session::instance();

		$action_name = Request::instance()->action;

		if (($this->auth_required !== FALSE && Auth::instance()->logged_in($this->auth_required) === FALSE)
		|| (is_array($this->secure_actions) && array_key_exists($action_name, $this->secure_actions) && 
		Auth::instance()->logged_in($this->secure_actions[$action_name]) === FALSE)) {

			if (!Auth::instance()->logged_in()){

				exit('no permission');
			}
		}


                $this->request->headers['Content-type'] = 'application/x-javascript';
	}
}