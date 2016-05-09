<?php
/**
 * Core file
 *
 * @author Vince Wooll <sales@jomres.net>
 * @version Jomres 9
 * @package Jomres
 * @copyright	2005-2016 Vince Wooll
 * Jomres (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly.
 **/
 
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
define("TRANSACTION_ID" , time() );
define("PRODUCTION" , false ); // Set this to true in a production environment
define("JOMRES_API_CMS_ROOT" ,dirname(dirname(dirname(__FILE__))));
define("JOMRES_API_JOMRES_ROOT" ,dirname(dirname(__FILE__)) );

if (!PRODUCTION)
	{
	ini_set('display_errors', '1');
	}

$logger = new Logger('api');
$logger->pushHandler(new StreamHandler('../temp/monolog/jomres_api.log', Logger::DEBUG));

if (isset($_POST['grant_type']) && isset($_POST['grant_type']) == 'client_credentials')
	{
	$client_id = filter_var($_POST['client_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
	$logger->addInfo('Received a token request from '.$client_id);
	require_once __DIR__.'/oauth/token.php';
	}
else
	{
	$request = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	$logger->addInfo(TRANSACTION_ID.' Received a token which sent '.$request);
	require_once __DIR__.'/oauth/resource.php';
	}

if (!defined('_JOMRES_INITCHECK'))
define('_JOMRES_INITCHECK', 1 );
	
define('API_STARTED', true );

$token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());


$scopes = explode("," , $token['scope']);

require 'classes/validate_scope.class.php';
require 'classes/validate_property_access.class.php';
require 'classes/call.class.php';
require 'classes/call_self.class.php';
require 'classes/all_api_features.class.php';


try
	{
	$CONFIG = new JConfig();
	$dsn		= 'mysql:dbname='.$CONFIG->db.';host='.$CONFIG->host ;
	Flight::register(
		'db', 
		'PDO', 
		array(
			$dsn, 
			$CONFIG->user, 
			$CONFIG->password , 
			array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES => false
				)
			)
		);

	$api_features	= new all_api_features();
	$features_files	= $api_features->get();

	Flight::register( 'validate_scope', 'validate_scope' , array( $scopes));

	Flight::set("token" , $token);
	Flight::set("user_id" , $token['user_id']);
	Flight::set("scopes" , explode("," , $token['scope']));
	Flight::set("dbprefix" , $CONFIG->dbprefix);
	Flight::set("features_files" , $features_files);

	require "routes.php";

	Flight::start();

	}
catch(Exception $e) 
	{
	$response = Flight::request_response();
	$backtrace = debug_backtrace();
	echo json_encode($response);
	}


function validate_property_uid($property_uid = '')
	{
	if ($property_uid == 0)
		return false;
	
	$users_properties = get_self($call = 'properties/all');
	if (!in_array ($property_uid , $users_properties ) )
		return false;
	return true;
	}