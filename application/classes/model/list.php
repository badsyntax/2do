<?php
class Model_List extends ORM {

	protected $_has_many = array('todos' => array());

	public function get_todos($user_id=0, $date=''){

		$todos = new Model_Todo;
		$todos
			->where('list_id', '=', $this->id)
			->where('user_id', '=', (int) $user_id)
			->order_by('sequence', 'ASC');

		if (preg_match('/^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{4,4}$/', $date)){

			$todos->where_open();

			$todos->where(DB::expr("DATE_FORMAT(date, '%d/%m/%Y')"), '=', $date);

			$todos->or_where_open();

			list($day, $month, $year) = explode('/', $date);

			$todos->where(DB::expr('UNIX_TIMESTAMP(date)'), '<', mktime(0, 0, 0, (int) $month, (int) $day, (int) $year));

			$todos->where('complete', '=', FALSE);

			$todos->or_where_close();

			$todos->where_close();
		} else exit;

		return $todos->find_all();
	}

}
