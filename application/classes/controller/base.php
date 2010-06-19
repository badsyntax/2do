<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_Base extends Controller_Template {
  
	public $template = 'template';

	public $auth_required = FALSE;

	public $secure_actions = FALSE;
  
	public function before() {

		parent::before();

		$this->session = Session::instance();

		$this->user = Auth::instance()->get_user();

		$action_name = Request::instance()->action;

		if (($this->auth_required !== FALSE && Auth::instance()->logged_in($this->auth_required) === FALSE)
		|| (is_array($this->secure_actions) && array_key_exists($action_name, $this->secure_actions) && 
		Auth::instance()->logged_in($this->secure_actions[$action_name]) === FALSE)) {

			if (Auth::instance()->logged_in()){

				Request::instance()->redirect('403');
			} else {

				Request::instance()->redirect('signin');
			}
		}

		if ($this->auto_render) {

			$this->template->title	 = '';
			$this->template->content = '';
			
			$this->template->styles = array();
			$this->template->scripts = array();
		}
	}
	
	public function after() {
	
		if ($this->auto_render) {

			$styles = array(
				'media/css/smoothness/jquery-ui.css' => 'screen, projection',
				'media/css/screen.css' => 'screen, projection'
			);
  
			$scripts = array(
				'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js',
				'media/js/jquery-ui.js',
				'media/js/global.js',
			);
		
			$this->template->styles = array_merge( $this->template->styles, $styles );
			$this->template->scripts = array_merge( $this->template->scripts, $scripts );
		}

		parent::after();
	}

	public function action_403(){
	      $this->template->title = '403';
	      $this->template->content = 'You do not have permission to view this page';
	}

}
