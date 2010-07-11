<?php
class Model_Task extends ORM {

	protected $_belongs_to = array('list' => array());

	protected $_rules = array(
		'time' => array(
			'not_empty'  => NULL,
			'min_length' => array(4),
			'max_length' => array(32)
		)
	);

	protected $_ignored_columns = array('id');

	protected $_callbacks = array(
		'time' => array('validate_time_format')
	);

	public function validate_time_format(Validate $array, $field) {

		// eg: 34 hrs
		if (!preg_match("/([0-9]{1,3}\s?[a-z]{1,6})/", $array[$field])) {

			$array->error($field, 'invalid', array($array[$field]));
		}
	}


	public function shift_sequences($offset=0){

		foreach($this->order_by('sequence', 'ASC')->find_all() as $i => $todo){
			$todo->sequence = $i + $offset;
			$todo->save();
		}
	}

	public function where_date($date='', $date_field='date'){

		if ($this->__check_date($date)){

			list($day, $month, $year) = explode('/', $date);

			$timestamp = mktime(23, 59, 59, (int) $month, (int) $day, (int) $year);

			$this->where(DB::expr("UNIX_TIMESTAMP({$date_field})"), '<=', $timestamp);
		}

		return $this;
	}

	public function get_completed($user_id=0, $date=''){

		if ($this->__check_date($date)){

			list($day, $month, $year) = explode('/', $date);

			$timestamp = mktime(23, 59, 59, (int) $month, (int) $day, (int) $year);
			
			$this->where(DB::expr('DATE_FORMAT(completed_date, \'%d/%m/%Y\')'), '=', $date);
		}

		return $this
			->where('deleted', '=', FALSE)
			->where('user_id', '=', (int) $user_id)
			->where('complete', '=', TRUE)
			->order_by('sequence', 'ASC')
			->find_all();
	}

	private function __check_date($date=''){

		return preg_match('/^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{4,4}$/', $date);
	}

	public function save(){

		if (preg_match_all("/([0-9]{1,3}\s?[a-z]{1,6})/", $this->time, $matches)){

			$tot_time = 0;

			foreach($matches[0] as $match) {

				preg_match("/([0-9]+)\s?([a-z]+)/", $match, $val);

				$time = $val[1];
				$type = $val[2][0];

				switch($type) {
					case 'm':
						$time *= 60;
						break;
					case 'h':
						$time *= 3600;
						break;
					case 'd':
						$time *= 86400;
						break;
				}

				$tot_time += $time;
			}

			$this->time = $tot_time;
		}

		return parent::save();
	}

	public function delete($id = NULL){

		$this->deleted = TRUE;

		return $this->save();
	}

	public function complete(){

		$this->complete = TRUE;
		$this->completed_date = DB::expr('NOW()');

		return $this->save();
	}

	public function incomplete(){

		$this->complete = FALSE;
		$this->completed_date = NULL;

		return $this->save();
	}

}
