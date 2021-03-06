<?php

/* Define all the configuration of this website */
$GLOBALS['config']=array();

/* [DATABASE] */
/* The hostname on which the Mysql database server resides */
$GLOBALS['config']['db_host']='';
/* The name of the Mysql database */
$GLOBALS['config']['db_name']='';
/* The username for Mysql */
$GLOBALS['config']['db_username']='';
/* The password for Mysql */
$GLOBALS['config']['db_passwd']='';
/*Driver-specific connection options for Mysql (see https://www.php.net/manual/en/ref.pdo-mysql.php) */
$GLOBALS['config']['db_options']=array();
/* Format used for storing date in the db */
$GLOBALS['config']['db_date_format']='Y-m-d H:i:s';

/* [GUEST] */
/* Id associated with the invited role */
$GLOBALS['config']['guest_id']=1;
/* Nickname associated with the invited role */
$GLOBALS['config']['guest_nickname']='guest';
/* Password associated with the invited role */
$GLOBALS['config']['guest_password']='guest';
/* Role name associated with the invited role */
$GLOBALS['config']['guest_role']='guest';

/* [IDENTITY] */
/* Name of the website */
$GLOBALS['config']['general_name']='';
/* Email of the website administrator */
$GLOBALS['config']['general_email']='';

/* [USER] */
/* Default user configuration */
$GLOBALS['config']['user_config']=array('lang' => 'EN');

/* [PAGE] */
/* Default application */
$GLOBALS['config']['default_application']='home';
/* Define the error page (better to use $e in it) */
$GLOBALS['config']['error_page']=array(
	'application' => 'error',
	'action'      => 'error',
);
/* Default configuration for page */
$GLOBALS['config']['default_config']=array();
/* The name of the content_type used (if defined) */
$GLOBALS['config']['default_config']['content_type']='HTML';
/* If notifications have to be displayed */
$GLOBALS['config']['default_config']['notification']=True;
/* Array which will be the arguments of the constructor of the page if content_type is not defined */
/* $GLOBALS['config']['default_config']['content']=array(
	'template' => './asset/html/pageelement/html/template.html',
	'elements' => array(),
); */
/* Array which will be the arguments of the constructor of the notification (if displayed) */
/* $GLOBALS['config']['default_config']['notification_element']=array(
	'template' => './asset/html/pageelement/html/htmlnotification/template.html',
	'elements' => array(),
); */

/* [ROUTER] */
/* Default Router mode */
$GLOBALS['config']['route_mode']=2;
/* Name of the parameter array for generating or retrieving URL */
$GLOBALS['config']['route_parameter']='parameters';
/* If the router mode cannot be changed with the URL */
$GLOBALS['config']['route_mode_strict']=False;

/* [PATH] */
/* Directory where lang definitions are */
$GLOBALS['config']['path_lang']='./lang/';
/* Directory where lang definitions are */
$GLOBALS['config']['path_config']='./config/';
/* Directory where log files are */
$GLOBALS['config']['path_log']='./log/';
/* Directory where mod files are */
$GLOBALS['config']['path_mod']='./mod/';
/* Directory where assets are */
$GLOBALS['config']['path_asset']='./asset/';
/* Directory where template are */
$GLOBALS['config']['path_template']='./asset/html/';
/* Directory where avatar are */
$GLOBALS['config']['path_avatar']='./asset/img/avatar/';
/* Default name of template file */
$GLOBALS['config']['template_filename']='template.html';
/* Directory where page are */
$GLOBALS['config']['path_page']='./page/';
/* Default name of page file */
$GLOBALS['config']['page_filename']='page.php';

/* [PAGE PARAMETERS] */
/* Page parameters which can be used in every page of this website */
$GLOBALS['config']['page_general_parameters']=array();
/* lang */
/* Description of the this parameter */
$GLOBALS['config']['page_general_parameters']['lang']=array();
/* If this parameter is necessary to load the page */
$GLOBALS['config']['page_general_parameters']['lang']['necessary']=False;
/* Regex characterizing this parameter */
$GLOBALS['config']['page_general_parameters']['lang']['regex']='^(EN|FR)$';

/* [LANG] */
/* Available language */
$GLOBALS['config']['lang_available']=array();
/* EN */
/* Description of this language */
$GLOBALS['config']['lang_available']['EN']=array();
/* Abbreviation of language */
$GLOBALS['config']['lang_available']['EN']['abbr']='EN';
/* Name of the language in this very language */
$GLOBALS['config']['lang_available']['EN']['full']='English';
/* FR */
/* Description of this language */
$GLOBALS['config']['lang_available']['FR']=array();
/* Abbreviation of language */
$GLOBALS['config']['lang_available']['FR']['abbr']='FR';
/* Name of the language in this very language */
$GLOBALS['config']['lang_available']['FR']['full']='Français';

/* [CONTENT TYPE] */
/* Default available content type */
$GLOBALS['config']['default_content_type']=array();
/* HTML */
/* Description of this content type */
$GLOBALS['config']['default_content_type']['html']=array();
/* Name of this content type */
$GLOBALS['config']['default_content_type']['html']['name']='html';
/* PageElement corresponding to the base content of this content type */
$GLOBALS['config']['default_content_type']['html']['content']='HTML';
/* PageElement corresponding to the base content of a notification for this content type*/
$GLOBALS['config']['default_content_type']['html']['notification']='html\HTMLNotification';

/* [HASH] */
/* Define the hash cost dor password */
$GLOBALS['config']['hash_cost']=6;

/* [LOG] */
/* Define the logging level */
$GLOBALS['config']['log_level']=2;

?>
