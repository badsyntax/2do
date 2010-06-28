<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Reports extends Controller_Base {

	public $auth_required = array('login');

	public function action_index(){

		$this->template->title = '2do : reports';

		$reports_template = View::factory('page/reports');

		$reports_template->total_tasks = count(ORM::factory('task')->where('user_id', '=', $this->user->id)->find_all());

		$reports_template->total_completed = count(ORM::factory('task')->get_completed( $this->user->id ));

		$this->template->content = $reports_template;
	}

	public function action_download(){

		$this->template->title = '2do : download reports';

		$tmp_file = '/tmp/'.time().$this->user->id.'.csv';

		DB::query(NULL, '
			SELECT content, date, completed_date INTO OUTFILE "'.$tmp_file.'"
			FIELDS TERMINATED BY \',\' OPTIONALLY ENCLOSED BY \'"\'
			LINES TERMINATED BY "\n"
			FROM tasks
			WHERE user_id = '.$this->user->id
		)->execute();

		$this->request->send_file($tmp_file, 'yourtasks.csv');
	}

}
