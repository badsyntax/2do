<?php
	ini_set('include_path', 'modules/openid');

	require_once Kohana::find_file('Auth', 'OpenID/Consumer');
	require_once Kohana::find_file('Auth', 'OpenID/FileStore');
	require_once Kohana::find_file('Auth', 'OpenID/SReg');
	require_once Kohana::find_file('Auth', 'OpenID/PAPE');
