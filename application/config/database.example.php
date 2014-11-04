<?php defined('SYSPATH') or die('No direct access allowed.');

return
	($_SERVER['HTTP_HOST'] !== 'dev.2do.me.uk' && $_SERVER['HTTP_HOST'] !== 'm.dev.2do.me.uk')
		?
		array
		(
			'default' => array
			(
				'type'       => 'mysql',
				'connection' => array(
					'hostname'   => 'localhost',
					'username'   => 'root',
					'password'   => '',
					'persistent' => FALSE,
					'database'   => '2do',
				),
				'table_prefix' => '',
				'charset'      => 'utf8',
				'caching'      => FALSE,
				'profiling'    => TRUE,
			)
		)
		:
		array
		(
			'default' => array
			(
				'type'       => 'mysql',
				'connection' => array(
					'hostname'   => 'localhost',
					'username'   => 'root',
					'password'   => '',
					'persistent' => FALSE,
					'database'   => '2do_dev',
				),
				'table_prefix' => '',
				'charset'      => 'utf8',
				'caching'      => FALSE,
				'profiling'    => TRUE,
			)
		);
