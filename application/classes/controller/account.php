<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Account extends Controller_Base {

	public $auth_required = FALSE;
 
	public function action_sign_in(){

		if(Auth::instance()->logged_in()){

			Request::instance()->redirect('profile');		
		}

		$this->template->title = 'Sign in';
		$content = $this->template->content = View::factory('account/signin');

		if ($_POST) {

			$status = ORM::factory('user')->login($_POST);
 
			if ($status) {		
				Request::instance()->redirect('/');
			} else {
				$content->errors = $_POST->errors('signin');
			}
		}
	}
	
	public function action_profile(){

		if(!Auth::instance()->logged_in()){

			Request::instance()->redirect('sign-in');
		}
		
		$profile = View::factory('account/profile');
		$profile->user = $this->user;

		$this->template->title = 'Profile';
		$content = $this->template->content = $profile;

		if ($_POST) {

			$user = ORM::factory('user', (int) $_POST['user_id']);

			$post = $user->validate_update($_POST);

			if ($post->check()) {

				$user->values($post);
				$user->username = $user->email;

				$user->save();

			} else {

				#Get errors for display in view
				$content->errors = $post->errors('register');
			}
		}
	}
 

	function action_sign_up(){

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

	public function action_sign_out(){

		Auth::instance()->logout();

		Request::instance()->redirect('/');		
	}

}
