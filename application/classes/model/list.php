<?php
class Model_List extends ORM {

	protected $_has_many = array('tasks' => array());

	public function get_tasks($user_id=0, $date=''){

		$tasks = new Model_Task;

		return $tasks
			->where('deleted', '=', FALSE)
			->where('complete', '=', FALSE)
			->where('list_id', '=', $this->id)
			->where('user_id', '=', (int) $user_id)
			->order_by('sequence', 'ASC')
			->where_date($date)
			->find_all();

	}

	public function get_tasks_completed($user_id=0, $date=''){

		$tasks = new Model_Task;

		return $tasks
			->where('deleted', '=', FALSE)
			->where('complete', '=', TRUE)
			->where('list_id', '=', $this->id)
			->where('user_id', '=', (int) $user_id)
			->order_by('sequence', 'ASC')
			->where_date($date)
			->find_all();

	}
}
