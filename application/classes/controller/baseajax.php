<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_BaseAjax extends Controller {
  
	public $auth_required = array('login');

	public $secure_actions = FALSE;
  
	public function before() {

		$this->mobile = strstr($_SERVER['HTTP_HOST'], 'm.');

		parent::before();
		
		$this->request->headers['Content-type'] = 'application/x-javascript';

		$this->session = Session::instance();
		
		$this->user = Auth::instance()->get_user();

		$action_name = Request::instance()->action;

		if (($this->auth_required !== FALSE && Auth::instance()->logged_in($this->auth_required) === FALSE)
		|| (is_array($this->secure_actions) && array_key_exists($action_name, $this->secure_actions) && 
		Auth::instance()->logged_in($this->secure_actions[$action_name]) === FALSE)) {

			if (!Auth::instance()->logged_in()){

				$response = array(
					'outcome' => false,
					'message' => 'Error: You have been logged out!'
				);

				exit(json_encode($response));
			}
		}

	}
}
