<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_Base extends Controller_Template {
  
	public $template = 'template';

	public $auth_required = FALSE;

	public $secure_actions = FALSE;
  
	public function before() {

		parent::before();
		
		set_include_path('application/vendor/');

		$this->session = Session::instance();

		$this->user = Auth::instance()->get_user();

		$action_name = Request::instance()->action;

		if (($this->auth_required !== FALSE && Auth::instance()->logged_in($this->auth_required) === FALSE)
		|| (is_array($this->secure_actions) && array_key_exists($action_name, $this->secure_actions) && 
		Auth::instance()->logged_in($this->secure_actions[$action_name]) === FALSE)) {

			if (Auth::instance()->logged_in()){

				Request::instance()->redirect('403');
			} else {

				Request::instance()->redirect('sign-in');
			}
		}

		if ($this->auto_render) {

			$this->template->title	 = '';
			$this->template->content = '';
			
			$this->template->styles = array();
			$this->template->scripts = array();
		}

		$request = Request::instance();
	}
	
	public function after() {
	
		$styles = array(
			'application/media/css/main.css'
		);
  
		$scripts = array(
			'application/media/js/jquery-ui.js',
			'application/media/js/modernizr-1.5.min.js',
			'application/media/js/global.js',
		);

		$this->template->styles = array();
		foreach(Media::instance()->styles($styles) as $style){

			$this->template->styles[] = preg_replace('/application\/media\//', '', $style);
		}
		
		$this->template->scripts = array();
		foreach(Media::instance()->scripts($scripts) as $script){

			$this->template->scripts[] = preg_replace('/application\/media\//', '', $script);
		}
		
                $this->request->response = $this->template;

		return parent::after();
	}

	public function action_403(){
	      $this->template->title = '403';
	      $this->template->content = 'You do not have permission to view this page';
	}

}
