<?php
/**
 * Index.php
 * This file is part of Mediajargo Base
 *
 * @version 	1.0
 * @author 	Jan-Karel Visser <jankarelvisser@gmail.com>
 * @website	http://www.mediajargo.nl
 * @repository	https://github.com/mediajargo
 * @copyright 	(c)2002-2011 Mediajargo / Jan-Karel Visser
 * @license 	http://creativecommons.org/licenses/by-sa/3.0/
 */



/*
* PHP4 compat required, thus a define
* big bummer
*/

	list($usec, $sec) = explode(" ", microtime());
	define('mdjrg_start_time', ((float)$usec + (float)$sec));


	
global $mdjrg_sys;

		//no config present? run with minimal configuration settings
		$mdjrg_sys=array(
			'system_folder'=>'mediajargo', //!important no trailing slash!
			'mdjrg_default_db'=>'mysql://root:password@hostname/databasename',
			'application_folder'=>'modules',//trailing slash!
			'default_module'=>'mediajargo',//name of the default module
			'start_controller'=>'index', //name of the start controllers
			'public_files'=>'files', //name of the public directory for css, images, js etc
			'cache_path'=>'mediajargo/cache',//location to the cache directory
			'salt_hashes'=>'my-unique-string-to-salt', //important!
			'index_uri'=>'index',//name of this file without file extension
			'javascript_lib'=>'mediajargo',//javascript librarie used by default
			'friendly_urls'=>TRUE,//mod rewrite enabled?
			'config_filename'=>'config',//name of config file located in/modulename/config/
			'force_url'=>'', //if friendly_urls are false, set index.php
			'allowed_routing'=>array('mediajargo'), //alowed modules, direct callable
			'cache_seconds'=>'3600',//time to cache
			'page_extension'=>'.html',//file extension to serve pages with
			'cookie_key'=>'secretword',//file extension to serve pages with
			'session_name'=>'Mediajargo', //cookies? 
			'session_expiration'=>'36000'//file extension to serve pages with
			);


/*
 * ------------------------------------------------------
 *  No need to edit from here
 * ------------------------------------------------------
 */	

	if (strpos($mdjrg_sys['system_folder'], '/') === FALSE)
	{
		
		if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
		{
			$mdjrg_sys['system_folder'] = realpath(dirname(__FILE__)).'/'.$mdjrg_sys['system_folder'];
		}

	}
	else
	{
		// Swap directory separators to Unix style for consistency
		$mdjrg_sys['system_folder'] = str_replace("\\", "/", $mdjrg_sys['system_folder']);
	}

	define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
	define('MEDIAJARGO', $mdjrg_sys['system_folder'].'/');
	define('MDJRG_ROOT',str_replace(pathinfo(__FILE__, PATHINFO_BASENAME), '', __FILE__));
	define('CACHE_PATH', ($mdjrg_sys['cache_path']?$mdjrg_sys['cache_path']:MEDIAJARGO.'cache'));
	define('MDJRG_SALT', $mdjrg_sys['salt_hashes']);
	
	if (is_dir($mdjrg_sys['application_folder']))
	{
		define('MODULEPATH', $mdjrg_sys['application_folder'].'/');
	}
	else
	{
		
		if ($mdjrg_sys['application_folder'] == '')
		{
			//force default path
			$mdjrg_sys['application_folder'] = MEDIAJARGO.'modules/';
		}

		define('MODULEPATH', $mdjrg_sys['application_folder']);
	}



/*
|---------------------------------------------------------------
| Boot
|---------------------------------------------------------------
|
| Let's mimic a Model View Chaos pattern...
| Time to route the request. 
|
*/
	
	include (MEDIAJARGO."base/base_loader".EXT);

	router();
	
	//the router returns a define where the controller lives
	//let's go!
	if(defined('mdjrg_page') && mdjrg_page!=false)
	{
		include mdjrg_page;
	}
	//else do nothing	
