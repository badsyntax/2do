<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Feedback extends Controller_Base {

	public $auth_required = array('login');
 
	public function action_index(){

		$this->template->title = '2do : feedback';

		$data = Validate::factory($_POST)
			->filter('subject', 'trim')
			->rule('subject', 'not_empty')
			->filter('message', 'trim')
			->filter('message', 'Security::xss_clean')
			->filter('message', 'strip_tags')
			->rule('message', 'not_empty');

		if ($data->check()){

			$transport = Swift_MailTransport::newInstance();

			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance("2do.me.uk feedback")
				->setFrom(array(
					$this->user->email => '2do.me.uk',
				))
				->setTo(array(
					'willis.rh@gmail.com' => 'Richard Willis',
				))
				->addPart($data['message'], 'text/plain');

			if ($mailer->send($message)) {

				// Redirect to avoid issues with refresh after POST
				$this->request->redirect(Request::instance()->uri.'?status=sent');
			}

		} else {

			$_POST = $data->as_array();

			$this->template->set_global('errors', $data->errors('contact'));
		}

		$this->template->content = View::factory('page/feedback' );

	}
}
