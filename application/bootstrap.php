<?php defined('SYSPATH') or die('No direct script access.');

//-- Environment setup --------------------------------------------------------

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('Europe/London');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
* Set the production status by the domain.
*/
Kohana::$environment = ($_SERVER['HTTP_HOST'] !== 'dev.2do.me.uk' and $_SERVER['HTTP_HOST'] !== 'm.dev.2do.me.uk') ? Kohana::PRODUCTION : Kohana::DEVELOPMENT;
//Kohana::$environment = Kohana::PRODUCTION;

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

//-- Configuration and initialization -----------------------------------------

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url	  path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"	     index.php
 * - string   charset	  internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory		     APPPATH/cache
 * - boolean  errors	  enable or disable error handling		     TRUE
 * - boolean  profile	  enable or disable internal profiling		     TRUE
 * - boolean  caching	  enable or disable internal caching		     FALSE
 */
Kohana::init(array(
	'base_url'   => '/',
	'index_file' => FALSE,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Kohana_Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	 'auth'       => MODPATH.'auth',       // Basic authentication
	 'cache'      => MODPATH.'cache',      // Caching with multiple backends
	 //'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	 'database'   => MODPATH.'database',   // Database access
	 //'image'      => MODPATH.'image',      // Image manipulation
	 'orm'	      => MODPATH.'orm',        // Object Relationship Mapping
	 'oauth'	=> MODPATH.'oauth',
	 //'pagination' => MODPATH.'pagination', // Paging of results
	 'media'	=> MODPATH.'media', // Paging of results
	 'openid'	=> MODPATH.'openid', // Paging of results
	 'event'	=> MODPATH.'event', // Paging of results
	 'swiftmailer'	=> MODPATH.'swiftmailer', // Paging of results
	 //'userguide'	=> MODPATH.'userguide',  // User guide and API documentation
));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

Route::set('list', 'list/<date>', array('date' => '.*'))
	->defaults(array(
		'controller' => 'list',
		'action' => 'index',
	));

Route::set('time', 'reports/time/<time>', array('time' => '.*'))
	->defaults(array(
		'controller' => 'reports',
		'action' => 'time',
	));

Route::set('auth', 'auth/openid_confirm/<openid>', array('openid' => '.*'))
	->defaults(array(
		'controller' => 'auth',
		'action' => 'confirm',
	));

Route::set('sign-in', 'sign-in')
	->defaults(array(
		'controller' => 'auth',
		'action' => 'sign_in'
	));

Route::set('sign-up', 'sign-up')
	->defaults(array(
		'controller' => 'auth',
		'action' => 'sign_up'
	));

Route::set('sign-out', 'sign-out')
	->defaults(array(
		'controller' => 'auth',
		'action' => 'sign_out'
	));

Route::set('profile', 'profile')
	->defaults(array(
		'controller' => 'auth',
		'action' => 'profile'
	));

Route::set('noaccess', '403')
	->defaults(array(
		'controller' => 'auth',
		'action' => '403'
	));

Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'home',
		'action'     => 'index',
	));

/**
 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
 * If no source is specified, the URI will be automatically detected.
 */
        
$request = Request::instance($_SERVER['PATH_INFO']);

try {
	 // Attempt to execute the response
	 $request->execute();

}
catch (ReflectionException $e) {

	Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));

	if ( Kohana::$environment === Kohana::DEVELOPMENT ) {

		throw $e;
	}

	$request->response = Request::factory('404')->execute();
}
catch (Exception404 $e) {

	Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));

	if ( Kohana::$environment === Kohana::DEVELOPMENT ) {
		throw $e;
	}

	$request->response = Request::factory('404')->execute();
}
catch (Kohana_Request_Exception $e) {

	Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));

	if ( Kohana::$environment === Kohana::DEVELOPMENT ) {
		throw $e;
	}

	$request->response = Request::factory('404')->execute();
}
catch (Exception $e) {

	Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));

	if ( Kohana::$environment === Kohana::DEVELOPMENT ) {

		throw $e;
	}
	 
	$request->response = Request::factory('500')->execute();
}


if ($request->response) {


	 // Get the total memory and execution time
	 $total = array(
	 	'{profiler}' => (string) View::factory('profiler/stats'),
		'{memory_usage}' => number_format((memory_get_peak_usage() - KOHANA_START_MEMORY) / 1024, 2).'KB',
		'{execution_time}' => number_format(microtime(TRUE) - KOHANA_START_TIME, 5).' seconds'
	);

	// Insert the totals into the response
	$request->response = strtr((string) $request->response, $total);
}

/**
* Display the request response.
*/
echo $request->send_headers()->response;
