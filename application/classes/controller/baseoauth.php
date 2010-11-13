<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_BaseOAuth extends Controller_Base {

	// OAuth
	protected $provider;
	protected $consumer;
	protected $token;
	protected $cookie;

	public function before()
	{
		parent::before();

		// The user is already logged in
		if (Auth::instance()->logged_in())
		{
			Request::instance()->redirect('');
		}

		// Load the configuration for this provider
		$config = Kohana::config('oauth.'.$this->provider);

		// Create a consumer from the config
		$this->consumer = OAuth_Consumer::factory($config);

		// Load the provider
		$this->provider = OAuth_Provider::factory($this->provider);

		if ($token = Cookie::get($this->cookie))
		{
			// Get the token from storage
			$this->token = unserialize($token);
		}
	}

}
