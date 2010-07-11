<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Reports extends Controller_Base {

	public $auth_required = array('login');

	public function action_index(){

		$this->template->title = '2do : reports';

		$reports_template = View::factory('page/reports');

		$reports_template->total_tasks = 
			count(
				ORM::factory('task')
				->where('user_id', '=', $this->user->id)
				->find_all()
			);

		$reports_template->total_completed = 
			count(
				ORM::factory('task')
				->get_completed( $this->user->id )
			);

		$this->template->content = $reports_template;
	}

	public function action_time($date=''){

		if (!$date or !preg_match("/^\d+\/\d+\/\d+$/", $date)) {
			
			throw new Exception('Invalid date');
		}

		list($month, $day, $year) = array_map('intval', explode('/', $date));

		if (!checkdate($month, $day, $year)) {

			throw new Exception('Invalid date');
		}

		$this->template->title = '2do : reports : time';

		$times_template = View::factory('page/reports_times');

		$times_template->date = $date;

		$this->template->content = $times_template;
	}

	public function action_download(){

		$tmp_file = '/tmp/'.time().$this->user->id.'.csv';

		DB::query(NULL, '
			SELECT content, date, completed_date INTO OUTFILE "'.$tmp_file.'"
			FIELDS TERMINATED BY \',\' OPTIONALLY ENCLOSED BY \'"\'
			LINES TERMINATED BY "\n"
			FROM tasks
			WHERE user_id = '.$this->user->id
		)->execute();

		$this->request->send_file($tmp_file, 'yourtasks.csv');

		unlink($tmp_file);
	}

}
