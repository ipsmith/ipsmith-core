<?php
define('LIB_DIR', dirname(__FILE__) );
define('IPS_DIR', LIB_DIR.'/..' );
define('LOG_DIR', IPS_DIR.'/logs' );

date_default_timezone_set('Europe/Berlin');
ini_set("display_errors", TRUE);
error_reporting(E_ALL);// ^ E_NOTICE ^ E_WARNING);

include ( LIB_DIR  .'/config.loader.php');

require ( LIB_DIR . '/3rdparty/smarty/libs/Smarty.class.php');
require ( LIB_DIR . '/loghandler.php');

$LogHandler = new LogHandlerClass();

use Doctrine\Common\ClassLoader,
     Doctrine\DBAL\DriverManager,
     Doctrine\DBAL as ORM;

require LIB_DIR.'/3rdparty/doctrine-dbal/Doctrine/Common/ClassLoader.php';

$classLoader = new ClassLoader('Doctrine', LIB_DIR.'/3rdparty/doctrine-dbal/');
$classLoader->register();

require ( LIB_DIR . '/loghandler-doctrine.php');


$doctrineConfig = new \Doctrine\DBAL\Configuration();

$databaseLogger = new IPSDebugStack($LogHandler->getLogger());
$doctrineConfig->setSQLLogger($databaseLogger );
$doctrineConnectionParams = array(
    'dbname' => $config["db"]["name"],
    'user' => $config["db"]["user"],
    'password' => $config["db"]["pass"],
    'host' => $config["db"]["host"],
    'driver' => $config["db"]["driver"],
);

$doctrineConnection = DriverManager::getConnection($doctrineConnectionParams, $doctrineConfig);

require (LIB_DIR.'/dbos/BaseObject.dbo.php');

require ( LIB_DIR  .'/ipsmith_autoload.php');
require ( LIB_DIR  .'/helper_functions.php');

// Setup templating
$smarty = new Smarty;

$smarty->debugging = $config["template"]["debugging"];
$smarty->caching = $config["template"]["caching"];
$smarty->force_compile = $config["template"]["force_compile"];
$smarty->use_sub_dirs = $config["template"]["use_sub_dirs"];

$smarty->cache_lifetime = $config["template"]["cache_lifetime"];

$smarty->template_dir = IPS_DIR.'/templates';
$smarty->cache_dir = IPS_DIR . '/cache/templates/caching';
$smarty->compile_dir = IPS_DIR .'/cache/templates/compile';

session_name($config["appidentifier"]);
$sessionid = session_id();
if(empty($sessionid))
{
  session_start();
  $_SESSION["userdata"] = array();
  $_SESSION["userdata"]["userid"] = 0;
  $_SESSION["userdata"]["username"] = "guest";
  $_SESSION["userdata"]["language"] = "en";
  $_SESSION["userdata"]["config"] = $config["defaultsettings"];
}

mysql_connect($config["db"]["host"],$config["db"]["user"],$config["db"]["pass"]);
mysql_select_db($config["db"]["name"]);

$translation = new TranslationManager($config);
$translation->load();

$system = array("currentversion"=>'0.0.1','versiontype'=>'dev');