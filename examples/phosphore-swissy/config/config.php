<?php

$GLOBALS['config']=array(
	/* db config */
		'db_host'        => 'localhost',					// The hostname on which the Mysql database server resides. 
		'db_name'        => 'swissy',						// The name of the Mysql database
		'db_username'    => 'root',							// The username for Mysql
		'db_passwd'      => '',					// The password for Mysql
		'db_options'     => array(),						// Driver-specific connection options for Mysql (see https://www.php.net/manual/en/ref.pdo-mysql.php)
		'db_date_format' => 'Y-m-d H:i:s',					// Format used for storing date in the db
			/* user */
				'guest_id'       => 1,				// id associated with the invited session
				'guest_nickname' => 'guest',		// nickname associated with the invited session
				'guest_password' => 'guest',		// password associated with the invited session
	/* route config */
		'route_parameter' => 'parameters',						// Name of the parameter array for generating or retrieving URL
		'route_mode'      => \core\Router::MODE_FULL_ROUTE,		// Default Router mode
	/* path config */
		'path_lang'   => './lang/',			// Directory where lang definitions are
		'path_config' => './config/',		// Directory where config files are
		/* template */
			'path_template'          => './asset/html/',			// Directory where templates are
			'path_template_filename' => 'template.html',			// Default name of template file
		/* page */
			'path_page_definition_root'     => './page/',			// Directory where php files which define a page are
			'path_page_definition_filename' => 'page.php',			// Default name of php file which define a page
	/* general config */
		'general_name'       => 'Swissy',							// Website name
		'general_email'      => 'gugus2000@protonmail.com',			// Email from the site administrator
		'general_parameters' => array(								// parameters which can be used in every page of this website
			'lang' => array(
				'necessary' => false,
				'regex'     => 'EN|FR',
			),
		),
		'general_langs'      => array(						// available languages
			'EN' => array(
				'abbr' => 'EN',
				'full' => 'English',
			),
			'FR' => array(
				'abbr' => 'FR',
				'full' => 'Français',
			),
		),
	/* user config */
		'user_config' => array(				// default visitor configuration
			'lang' => 'FR',
		),
	/* pageelement config  */
		'default_content_type' => array(
			array(
				'name'         => 'html',
				'content'      => 'HTML',
				'notification' => 'html\HTMLNotification',
			),
		),
		/* html */
			'default_head_metas'       => array(		// Metas which will be in each html page
				array(
					'http-equiv' => 'X-UA-Compatible',
					'content'    => 'IE=edge',
				),
				array(
					'name'    => 'viewport',
					'content' => 'width=device-width, initial-scale=1',
				),
			),
			'default_head_css'         => array('asset/css/style.css', 'asset/css/font.css'),		// Css which will be in each html page
			'default_head_javascripts' => array(),													// Javascripts which will be in each html page
			'notification_css'         => array('asset/css/notification.css'),						// Css which will be in each html page if a notification exist
			'notification_js'          => array(),													// Javascripts which will be in each html page if a notification exist
	/* page config  */
		'error_page' => array('application' => 'error', 'action' => 'error'),		// array which define the error page
		/* default */
			'default_config' => array(				// Default page config
				'content_type' => 'HTML',
				'notification' => true,
			),
			'default_application' => 'home',		// Default application
);

?>