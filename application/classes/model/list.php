<?php
class Model_List extends ORM {

	protected $_has_many = array('todos' => array());

	public function get_todos($user_id=0, $date=''){

		$todos = new Model_Todo;

		return $todos
			->where('deleted', '=', FALSE)
			->where('complete', '=', FALSE)
			->where('list_id', '=', $this->id)
			->where('user_id', '=', (int) $user_id)
			->order_by('sequence', 'ASC')
			->where_date($date)
			->find_all();

	}

	public function get_todos_completed($user_id=0, $date=''){

		$todos = new Model_Todo;

		$todos
			->where('deleted', '=', FALSE)
			->where('complete', '=', TRUE)
			->where('list_id', '=', $this->id)
			->where('user_id', '=', (int) $user_id)
			->order_by('sequence', 'ASC')
			->where_date($date)
			->find_all();

	}
}
