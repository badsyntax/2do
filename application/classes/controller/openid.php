<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Openid extends Controller_Base {

	public function action_consumer(){
		
		require Kohana::find_file('vendor', 'Auth/OpenID/PAPE'); 

		$this->template->title = '2do : openid login';

		$template_consumer = View::factory('openid/consumer');

		$template_consumer->pape_policy_uris = array(
			PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			PAPE_AUTH_MULTI_FACTOR,
			PAPE_AUTH_PHISHING_RESISTANT
		);

		$this->template->content = $template_consumer;
	}

	public function action_try_auth(){

		require Kohana::find_file('vendor', 'Auth/OpenID/Consumer'); 
		
		require Kohana::find_file('vendor', 'Auth/OpenID/FileStore'); 
		
		require Kohana::find_file('vendor', 'Auth/OpenID/SReg'); 
		
		require Kohana::find_file('vendor', 'Auth/OpenID/PAPE'); 

		$openid = $_GET['openid_identifier'];

		$store_path = "/tmp/_php_consumer_test";

		if (!file_exists($store_path) && !mkdir($store_path)) {
			print "Could not create the FileStore directory '$store_path'. ".
			" Please check the effective permissions.";
			exit(0);
		}

		$store = new Auth_OpenID_FileStore($store_path);

		$consumer = new Auth_OpenID_Consumer($store);

		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin($openid);

		// No auth request means we can't begin OpenID.
		if (!$auth_request) {
			die("Authentication error; not a valid OpenID.");
		}

		$sreg_request = Auth_OpenID_SRegRequest::build( // Required
				     array('nickname'),
				     // Optional
				     array('fullname', 'email'));

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

			$redirect_url = $auth_request->redirectURL(URL::site(NULL, TRUE), URL::site('openid/finish_auth', TRUE));

			// If the redirect URL can't be built, display an error
			// message.
			if (Auth_OpenID::isFailure($redirect_url)) {
				exit("Could not redirect to server: " . $redirect_url->message);
			} else {
				// Send redirect.
				header("Location: ".$redirect_url);
			}
		} else {

			$form_html = $auth_request->htmlMarkup(URL::site(NULL, TRUE), URL::site('openid/finish_auth', TRUE), false, array('id' => 'openid_message'));
			
			// Display an error if the form markup couldn't be generated;
			// otherwise, render the HTML.
			if (Auth_OpenID::isFailure($form_html)) {
				exit("Could not redirect to server: " . $form_html->message);
			} else {

				$this->template->content = $form_html.'<h2>Please wait</h2>';
			}

			$this->template->content = $form_html;
		}

	}

}
