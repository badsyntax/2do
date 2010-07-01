<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Controller_Base {

	public function before(){

		parent::before();

		require Kohana::find_file('vendor', 'Lightopenid/openid');
	}

	public function action_index(){

		try {
			if(!isset($_GET['openid_mode'])) {

				if(isset($_POST['openid_identifier'])) {
				     $openid = new LightOpenID;


					$openid->identity = $_POST['openid_identifier'];
				     	die($openid->authUrl());
					Request::instance()->redirect($openid->authUrl());
			       }
				?>
				<form action="/auth" method="post">
				    OpenID: <input type="text" name="openid_identifier" /> <button>Submit</button>
				</form>
				<?php
				exit;
			} elseif($_GET['openid_mode'] == 'cancel') {

				echo 'User has canceled authentication!';
			} else {

				$openid = new LightOpenID;
				echo 'User ' . ($openid->validate() ? $_GET['openid_identity'] . ' has ' : 'has not ') . 'logged in.';
			}

		} catch(ErrorException $e) {

			echo $e->getMessage();
			exit;
		}

		//Request::instance()->redirect('/auth/consumer');
	}


}
