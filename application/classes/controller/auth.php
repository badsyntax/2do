<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @name OpenID auth controller 
 * @author Richard Willis
 * @depends kohana3, auth module
 * @description This is an example Kohana controller using php-openid to authenticate an OpenID
 * service, largely based on the php-openid consumer examples here:
 * http://github.com/openid/php-openid/tree/master/examples/consumer/
 *
 */

class Controller_Auth extends Controller_Base {
	
	private $store_path = '/tmp/_php_consumer_test';

	public function before(){

		parent::before();

		set_include_path('application/vendor');

		require_once Kohana::find_file('vendor', 'Auth/OpenID/Consumer');
		require_once Kohana::find_file('vendor', 'Auth/OpenID/FileStore');
		require_once Kohana::find_file('vendor', 'Auth/OpenID/SReg');
		require_once Kohana::find_file('vendor', 'Auth/OpenID/PAPE');

		if (!file_exists($this->store_path) && !@mkdir($this->store_path)) {

			throw new Exception("Could not create the FileStore directory '{$store_path}'. Please check the effective permissions.");
		}
	}

	public function action_index(){

		Request::instance()->redirect('/');
	}

	public function action_finish(){
		
		$openid = @$_GET['openid_identity'];

		if (!$openid) Request::instance()->redirect('/');

		$store = new Auth_OpenID_FileStore($this->store_path);

		$consumer = new Auth_OpenID_Consumer($store);

		$response = $consumer->complete(URL::site('auth/finish', TRUE));

		if ($response->status == Auth_OpenID_CANCEL) {

			throw new Exception("OpenID authentication cancelled.");

		} else if ($response->status == Auth_OpenID_FAILURE) {

			throw new Exception("OpenID authentication failed: {$response->message}");

		} else if ($response->status == Auth_OpenID_SUCCESS) {

			$openid = $response->getDisplayIdentifier();

			$user = ORM::factory('user')
				->where('username', '=', htmlentities($openid))
				->find();

			if (!$user->id) {

				Request::instance()->redirect('/auth/confirm?openid='.urlencode($openid));
			}

			Auth::instance()->force_login($user);

			Request::instance()->redirect('/');
		}
	}

	public function action_confirm(){

		if (!$_POST) {
			$template_auth = new View('openid/auth_confirm');
			$template_auth->openid = @$_GET['openid'];
			$this->template->content = $template_auth;
		} 
		else if (isset($_POST['agree'])) {

			$openid = @$_POST['openid'];

			$data = array(
				'username' => $openid,
				'email' => $openid,
				'password' => $openid,
				'password_confirm' => $openid,
				'remember' => TRUE
			);
			$user = ORM::factory('user');

			$post = $user->validate_create($data, false);

			if ($post->check()) {

				$user->values($post);

				$user->save();

				$user->add('roles', new Model_Role(array('name' =>'login')));

				Auth::instance()->force_login($user);
			} else {
				die(print_r($post->errors('register')));
			}

			Request::instance()->redirect('/');
		}
	}

	public function action_try(){

		$template_auth = new View('openid/auth_login');

		$openid = @$_GET['openid_identity'];
		
		if (!$openid) {

			Request::instance()->redirect('/');
		}

		$store = new Auth_OpenID_FileStore($this->store_path);

		$consumer = new Auth_OpenID_Consumer($store);

		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin($openid);

		if (!$auth_request) {

			throw new Exception('Authentication error; not a valid OpenID.');
		}

		$sreg_request = Auth_OpenID_SRegRequest::build( array('nickname'), array('fullname', 'email') );

		if ($sreg_request) {

			$auth_request->addExtension($sreg_request);
		}

		$policy_uris = @$_GET['policies'];

		$pape_request = new Auth_OpenID_PAPE_Request($policy_uris);

		if ($pape_request) {

			$auth_request->addExtension($pape_request);
		}

		// Redirect the user to the OpenID server for authentication.
		// Store the token for this authentication so we can verify the
		// response.

		// For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
		// form to send a POST request to the server.
		if ($auth_request->shouldSendRedirect()) {

			$redirect_url = $auth_request->redirectURL(URL::site(NULL, TRUE), URL::site('auth/finish', TRUE));

			// If the redirect URL can't be built, display an error
			// message.
			if (Auth_OpenID::isFailure($redirect_url)) {
			
				throw new Exception('Could not redirect to server: '.$redirect_url->message);
			}

			// Send redirect.
			Request::instance()->redirect($redirect_url);
		} else {

			$form_html = $auth_request->htmlMarkup(
				URL::site(NULL, TRUE), 
				URL::site('auth/finish', TRUE), 
				false, 
				array('id' => 'openid_message')
			);
			
			// Display an error if the form markup couldn't be generated;
			// otherwise, render the HTML.
			if (Auth_OpenID::isFailure($form_html)) {

				throw new Exception('Could not redirect to server: ' . $form_html->message);
			}
				
			$template_auth->form = $form_html;
		}
				
		$this->template->content = $template_auth;
	}
}
