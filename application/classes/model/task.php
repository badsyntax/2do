<?php
class Model_Todo extends ORM {

	protected $_belongs_to = array('list' => array());

	public function shift_sequences($offset=0){

		foreach($this->order_by('sequence', 'ASC')->find_all() as $i => $todo){
			$todo->sequence = $i + $offset;
			$todo->save();
		}
	}

	public function where_date($date=''){

		if (preg_match('/^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{4,4}$/', $date)){

			$this
				->where_open()
				->where(DB::expr("DATE_FORMAT(date, '%d/%m/%Y')"), '=', $date)
				->or_where_open();

			list($day, $month, $year) = explode('/', $date);

			$this
				->where(DB::expr('UNIX_TIMESTAMP(date)'), '<', mktime(0, 0, 0, (int) $month, (int) $day, (int) $year))
				->where('complete', '=', FALSE)
				->or_where_close()
				->where_close();
		}

		return $this;
	}

	public function get_completed($user_id=0, $date=''){

		return $this
			->where('deleted', '=', FALSE)
			->where('complete', '=', TRUE)
			->where('user_id', '=', (int) $user_id)
			->order_by('sequence', 'ASC')
			->where_date($date)
			->find_all();
	}


	public function save(){

		return parent::save();
	}

	public function __delete(){

		$this->deleted = TRUE;
		$this->save();
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
