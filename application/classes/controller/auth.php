<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Controller_Base {

	public function before(){

		parent::before();

		require Kohana::find_file('vendor', 'Auth/OpenID/Consumer');
		require Kohana::find_file('vendor', 'Auth/OpenID/FileStore');
		require Kohana::find_file('vendor', 'Auth/OpenID/SReg');
		require Kohana::find_file('vendor', 'Auth/OpenID/PAPE');
	}

	public function action_index(){

		Request::instance()->redirect('/');
	}

	public function action_finish(){
		
		$template_auth = View::factory('openid/auth');

		$openid = @$_GET['openid_identity'];

		if (!$openid) Request::instance()->redirect('/');

		$store_path = "/tmp/_php_consumer_test";

		if (!file_exists($store_path) && !mkdir($store_path)) {
			print "Could not create the FileStore directory '$store_path'. ".
			" Please check the effective permissions.";
			exit(0);
		}

		$store = new Auth_OpenID_FileStore($store_path);

		$consumer = new Auth_OpenID_Consumer($store);

		// Complete the authentication process using the server's
		// response.
		$response = $consumer->complete(URL::site('auth/finish', TRUE));

		// Check the response status.
		if ($response->status == Auth_OpenID_CANCEL) {

			// This means the authentication was cancelled.
			$msg = 'Verification cancelled.';

		} else if ($response->status == Auth_OpenID_FAILURE) {

			// Authentication failed; display the error message.
			$msg = "OpenID authentication failed: " . $response->message;

		} else if ($response->status == Auth_OpenID_SUCCESS) {

			// authentication succeeded
			$openid = $response->getDisplayIdentifier();
			$esc_identity = htmlentities($openid);

			$user = ORM::factory('user')->get_by_openid($esc_identity);

			if (!$user->id) {
				$user->username = $esc_identity;
				$user->email = $esc_identity;
				$user->password = $esc_identity;
				$user->openid = $esc_identity;
				$user->save();
				$user->add('roles', new Model_Role(array('name' =>'login')));
			}

			Auth::instance()->force_login($user);

			Request::instance()->redirect('/');
		}

		$template_auth->msg = @$msg;
		$template_auth->success = @$success;

		$this->template->content = $template_auth;
	}
	public function action_try(){

		$template_auth = new View('openid/auth_login');
		
		$openid = @$_GET['openid_identity'];
		
		if (!$openid) Request::instance()->redirect('/');

		$store_path = "/tmp/_php_consumer_test";

		(!file_exists($store_path) && !mkdir($store_path)) and
			exit("Could not create the FileStore directory '$store_path'. Please check the effective permissions.");

		$store = new Auth_OpenID_FileStore($store_path);

		$consumer = new Auth_OpenID_Consumer($store);

		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin($openid);

		// No auth request means we can't begin OpenID.
		!$auth_request and die("Authentication error; not a valid OpenID.");

		$sreg_request = Auth_OpenID_SRegRequest::build( // Required
				     array('nickname'),
				     // Optional
				     array('fullname', 'email'));

		if ($sreg_request) {
			$auth_request->addExtension($sreg_request);
		}

		$policy_uris = @$_GET['policies'];

		$pape_request = new Auth_OpenID_PAPE_Request($policy_uris);

		$pape_request and $auth_request->addExtension($pape_request);

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

				exit("Could not redirect to server: " . $redirect_url->message);
			} else {

				// Send redirect.
				Request::instance()->redirect($redirect_url);
			}
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

				exit("Could not redirect to server: " . $form_html->message);

			} else {

				$template_auth->form = $form_html;
			}
		}
				
		$this->template->content = $template_auth;
	}
}
