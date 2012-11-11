<?php
define('LIB_DIR',dirname(__FILE__));
define('IPS_DIR',LIB_DIR.'/..');

date_default_timezone_set('Europe/Berlin');
ini_set("display_errors", TRUE);
error_reporting(E_ALL);// ^ E_NOTICE ^ E_WARNING);
define('LOG_DIR',IPS_DIR.'/logs');

include ( LIB_DIR  .'/config.inc.php');

require ( LIB_DIR . '/3rdparty/smarty/libs/Smarty.class.php');

function ipsmith_autoload($class) {
    include(LIB_DIR.'/classes/' . $class . '.class.php');
}
spl_autoload_register('ipsmith_autoload');

$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 1;
$smarty->force_compile = true;
$smarty->cache_dir = IPS_DIR . '/cache/templates/caching';
$smarty->compile_dir = IPS_DIR .'/cache/templates/compile';
$smarty->use_sub_dirs = true;
$smarty->template_dir = IPS_DIR.'/templates';
session_name('ipsmith-dev');
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

  function startsWith($check, $startStr) {
        if (!is_string($check) || !is_string($startStr) || strlen($check)<strlen($startStr)) {
            return false;
        }
 
        return (substr($check, 0, strlen($startStr)) === $startStr);
    }


$translation = new TranslationManager($config);
$translation->load();


function parseRequest($request)
{
    global $req;

    $vars = array('module','page','displaytype');

    foreach($vars as $var)
    {
	    if(isset($request[$var]) && !is_null($request[$var]))
    	{
	    	$req[$var] = strtolower($request[$var]);
        }
    }

   return $req;
}


function downloadUrl($url)
{
   $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,0);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
    $rawdata=curl_exec($ch);
    curl_close ($ch);

return $rawdata;
}
function parseApiRequest($request)
{
    $url = "https://api.ipsmith.org/".$request;

    $result = downloadUrl($url);

    $return = json_decode($result,true);

    return $return;
}

$system = array("currentversion"=>'0.0.1','versiontype'=>'dev');
