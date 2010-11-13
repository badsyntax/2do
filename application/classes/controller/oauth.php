<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Oauth extends Controller_BaseOAuth {

	// OAuth
	protected $provider = 'twitter';
	protected $cookie = 'oauth_token_twitter';

	public function action_index()
	{
		// We will need a callback URL for the user to return to
		$callback = URL::site($this->request->uri(array('action' => 'authorize')), Request::$protocol);

		// Add the callback URL to the consumer
		$this->consumer->callback($callback);

		// Get a request token for the consumer
		$token = $this->provider->request_token($this->consumer);

		// Store the request token
		Cookie::set($this->cookie, serialize($token));

		// Redirect to the provider's login page
		$this->request->redirect($this->provider->authorize_url($token));
	}

	public function action_authorize()
	{
		if ($this->token AND $this->token->token !== Arr::get($_GET, 'oauth_token'))
		{
			// Delete the token, it is not valid
			Cookie::delete($this->cookie);

			// Send the user back to the beginning
			Request::instance()->redirect($this->request->uri(array('action' => 'index')));
		}

		// Get the verifier
		$verifier = Arr::get($_GET, 'oauth_verifier');

		// Store the verifier in the token
		$this->token->verifier($verifier);

		// Exchange the request token for an access token
		$this->token = $this->provider->access_token($this->consumer, $this->token);

		// Store the access token
		Cookie::set($this->cookie, serialize($this->token));

		// At this point, we need to retrieve a unique twitter id for the user.
		$response = 
			OAuth_Request::factory('resource', 'GET', 'http://api.twitter.com/1/account/verify_credentials.json')
			->param('oauth_consumer_key', Kohana::config('oauth.twitter.key'))
			->param('oauth_token', $this->token)
			->sign(OAuth_Signature::factory('HMAC-SHA1'), $this->consumer, $this->token)
			->execute();

		$response = json_decode($response);

		$twitter_id = $response->screen_name;

		$user = ORM::factory('user')
		       ->where('username', '=', $twitter_id)
		       ->find();

		!$user->id AND Request::instance()->redirect('/auth/confirm?id='.$twitter_id);

		Auth::instance()->force_login($user);

		Session::instance()->set('notification', 'Succesfully logged in.');

		Request::instance()->redirect('/');
	}
}
