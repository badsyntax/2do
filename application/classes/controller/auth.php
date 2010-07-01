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

		Request::instance()->redirect('/auth/consumer');
	}

	public function action_consumer(){
		
		$this->template->title = '2do : openid login';

		$template_consumer = View::factory('openid/consumer');

		$template_consumer->pape_policy_uris = array(
			PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			PAPE_AUTH_MULTI_FACTOR,
			PAPE_AUTH_PHISHING_RESISTANT
		);

		$this->template->content = $template_consumer;
	}

	public function action_finish_auth(){
		
		$template_auth = View::factory('openid/auth');

		$openid = $_GET['openid_identity'];

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
		$response = $consumer->complete(URL::site('auth/finish_auth', TRUE));

		// Check the response status.
		if ($response->status == Auth_OpenID_CANCEL) {

			// This means the authentication was cancelled.
			$msg = 'Verification cancelled.';

		} else if ($response->status == Auth_OpenID_FAILURE) {

			// Authentication failed; display the error message.
			$msg = "OpenID authentication failed: " . $response->message;

		} else if ($response->status == Auth_OpenID_SUCCESS) {

			// This means the authentication succeeded; extract the
			// identity URL and Simple Registration data (if it was
			// returned).
			$openid = $response->getDisplayIdentifier();
			$esc_identity = htmlentities($openid);

			$success = sprintf('<p>You have successfully verified ' .
					   '<a href="%s">%s</a> as your identity.</p>',
					   $esc_identity, $esc_identity);

			if ($response->endpoint->canonicalID) {
			    $escaped_canonicalID = escape($response->endpoint->canonicalID);
			    $success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
			}

			$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

			$sreg = $sreg_resp->contents();

			if (@$sreg['email']) {
			    $success .= "  You also returned '".escape($sreg['email']).
				"' as your email.";
			}

			if (@$sreg['nickname']) {
			    $success .= "  Your nickname is '".escape($sreg['nickname']).
				"'.";
			}

			if (@$sreg['fullname']) {
			    $success .= "  Your fullname is '".escape($sreg['fullname']).
				"'.";
			}

			$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

			if ($pape_resp) {
				if ($pape_resp->auth_policies) {
					$success .= "<p>The following PAPE policies affected the authentication:</p><ul>";

					foreach ($pape_resp->auth_policies as $uri) {
						$escaped_uri = escape($uri);
						$success .= "<li><tt>$escaped_uri</tt></li>";
					}

					$success .= "</ul>";
				} else {
					$success .= "<p>No PAPE policies affected the authentication.</p>";
				}

				if ($pape_resp->auth_age) {
					$age = escape($pape_resp->auth_age);
					$success .= "<p>The authentication age returned by the " .
					    "server is: <tt>".$age."</tt></p>";
				}

				if ($pape_resp->nist_auth_level) {
					$auth_level = escape($pape_resp->nist_auth_level);
					$success .= "<p>The NIST auth level returned by the " .
					    "server is: <tt>".$auth_level."</tt></p>";
				}

			} else {
				$success .= "<p>No PAPE response was sent by the provider.</p>";
			}
		}

		$template_auth->msg = @$msg;
		$template_auth->success = @$success;

		$this->template->content = $template_auth;
	}
	public function action_try_auth(){
		
		$openid = $_GET['openid_identity'];

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

			$redirect_url = $auth_request->redirectURL(URL::site(NULL, TRUE), URL::site('auth/finish_auth', TRUE));

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
				URL::site('auth/finish_auth', TRUE), 
				false, 
				array('id' => 'openid_message')
			);
			
			// Display an error if the form markup couldn't be generated;
			// otherwise, render the HTML.
			if (Auth_OpenID::isFailure($form_html)) {

				exit("Could not redirect to server: " . $form_html->message);

			} else {

				exit($form_html.'<h2>Please wait</h2>');
			}

			exit($form_html);
		}
	}
}
