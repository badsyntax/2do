<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_Base extends Controller_Template {
  
	public $template = 'master_page';
	
	public $template_mobile = 'master_page_mobile';

	public $auth_required = FALSE;

	public $secure_actions = FALSE;

	public $cache_page = TRUE;

	public $mobile = FALSE;
  
	public function before() {

		$this->mobile = strstr($_SERVER['HTTP_HOST'], 'm.');

		$this->template = $this->mobile ? $this->template_mobile : $this->template;

		parent::before();
		
		$this->session = Session::instance();

		$this->user = Auth::instance()->get_user();

		$action_name = Request::instance()->action;

		if (
			($this->auth_required !== FALSE && Auth::instance()->logged_in($this->auth_required) === FALSE) 
			|| (is_array($this->secure_actions) && array_key_exists($action_name, $this->secure_actions) 
			&& Auth::instance()->logged_in($this->secure_actions[$action_name]) === FALSE)
		) {

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

		$this->check_cache();
	}
	
	public function after() {
	
		$styles = $this->mobile
			? array(
				'http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css',
				'application/media/css/main_mobile.css'
			) 
			: array(
				'application/media/css/main.css'
			);
  
		$scripts = $this->mobile 
			? array(
				'http://code.jquery.com/jquery-1.4.3.min.js',
				'application/media/js/jquery.mobile.js',
				//'application/media/js/global.js'
			)
			: array(
				'application/media/js/jquery.js',
				'application/media/js/jquery-ui.js',
				'application/media/js/global.js'
			);

		$this->template->styles = array();
		foreach(Media::instance()->styles($styles) as $style){

			$this->template->styles[] = preg_replace('/application\//', '', $style);
		}
		
		$this->template->scripts = array();
		foreach(Media::instance()->scripts($scripts) as $script){

			$this->template->scripts[] = preg_replace('/application\//', '', $script);
		}
                $this->request->response = $this->template;

		Event::run('routing.after');

		return parent::after();
	}

	public function action_403(){

	      $this->template->title = '403';
	      $this->template->content = 'You do not have permission to view this page';
	}

	private function check_cache(){

		if ( Kohana::$environment !== Kohana::PRODUCTION ) return;
		
		$cache_key = sha1( 
			( $this->user ? $this->user->id : 0 ) .
			$this->request->uri . 
			get_class($this) .
			implode('.', $_REQUEST)
		);

		$cache = Cache::instance()->get($cache_key);

		if ( !$this->cache_page ) {

			return;

		} else if ( !$cache ) {

			$cache_lifetime = 10;
			
			Event::add('routing.after', array($this, 'save_cache'), FALSE, array($cache_key, $cache_lifetime));

		} else {

			$this->request->send_headers();

			echo strtr($cache, array(
				'{profiler}' => (string) View::factory('profiler/stats'),
				'{execution_time}' => number_format(microtime(TRUE) - KOHANA_START_TIME, 5).' seconds'
			));

			exit;
		}
		
	}

	public function purge_cache($cache_key){

		Cache::instance()->delete($cache_key);
	}

	public function save_cache($cache_key, $cache_lifetime=FALSE){

		if ( !$this->cache_page ) {
		
			return;
		}

		if ($cache_lifetime === FALSE) {

			$cache_lifetime = PHP_INT_MAX;
		}

		Cache::instance()->set($cache_key, (string) $this->request->response, $cache_lifetime);
	}
}
