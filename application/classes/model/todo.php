<?php
class Model_Todo extends ORM {

	public function shift_sequences($offset=0){

		foreach($this->order_by('sequence', 'ASC')->find_all() as $i => $todo){
			$todo->sequence = $i + $offset;
			$todo->save();
		}
	}
}
