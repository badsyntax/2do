<?php
class Model_Task extends ORM {

	protected $_belongs_to = array('list' => array());

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

		return $this;
	}

	private function __check_date($date=''){

		return preg_match('/^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{4,4}$/', $date);
	}

	public function save(){

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
