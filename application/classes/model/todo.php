<?php
class Model_Todo extends ORM {

        protected $_belongs_to = array('list' => array());

	public function shift_sequences($offset=0){

		foreach($this->order_by('sequence', 'ASC')->find_all() as $i => $todo){
			$todo->sequence = $i + $offset;
			$todo->save();
		}
	}

	public function save($user_id=0, $content=''){

		return parent::save();
	}

	public function __delete(){
		
		parent::delete();
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
