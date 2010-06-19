<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Account extends Controller_Base {

	public $auth_required = FALSE;
 
	function action_index(){

		$this->template->content = 'test';	
	}

	function action_signin(){

		if(Auth::instance()->logged_in()){

			Request::instance()->redirect('profile');		
		}

		$this->template->title = 'Sign in';
		$content = $this->template->content = View::factory('account/signin');

		if ($_POST) {

			$status = ORM::factory('user')->login($_POST);
 
			if ($status) {		
				Request::instance()->redirect('profile');
			} else {
				$content->errors = $_POST->errors('signin');
			}
		}
	}

	function action_signup(){

		if(Auth::instance()->logged_in()){

			Request::instance()->redirect('profile');		
		}

		$this->template->title = 'Sign up'; 
		$content = $this->template->content = View::factory('account/signup');		
 
		if ($_POST) {

			$user = ORM::factory('user');	
 
			$post = $user->validate_create($_POST);			
 
			if ($post->check()) {

				#Affects the sanitized vars to the user object
				$user->values($post);
 
				#create the account
				$user->save();
 
				#Add the login role to the user
				$login_role = new Model_Role(array('name' =>'login'));
				$user->add('roles',$login_role);
 
				#sign the user in
				Auth::instance()->login($post['username'], $post['password']);
 
				#redirect to the user account
				Request::instance()->redirect('profile');
			}
			else {
				#Get errors for display in view
				$content->errors = $post->errors('register');
			}			
		}		
	}

	public function action_signout(){

		Auth::instance()->logout();

		Request::instance()->redirect('profile');		
	}

	function action_profile(){

		if(!Auth::instance()->logged_in()){

			Request::instance()->redirect('signin');
		}

		$this->template->title = 'Profile';
		$this->template->content = 'Profile';
	}
 
}
