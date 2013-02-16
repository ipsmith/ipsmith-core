<?php
/**
 * Project:	 IPSmith - Free ip address managing tool
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This Software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc., please join the
 * IPSmith mailing list. Go to http://www.ipsmith.org/lists
 *
 **/

define('LIB_DIR', dirname(__FILE__) );
define('IPS_DIR', LIB_DIR.'/..' );
define('LOG_DIR', IPS_DIR.'/logs' );
define('PUBLIC_DIR', IPS_DIR.'/../public');

date_default_timezone_set('Europe/Berlin');
ini_set("display_errors", TRUE);
error_reporting(E_ALL);// ^ E_NOTICE ^ E_WARNING);

include ( LIB_DIR  .'/config.loader.php');
require ( LIB_DIR . '/3rdparty/smarty/libs/Smarty.class.php');
require ( LIB_DIR . '/loghandler.php');

$LogHandler = new LogHandlerClass();

require ( LIB_DIR . '/doctrine_loader.php');
require ( LIB_DIR .'/ipsmith_autoload.php');
require ( LIB_DIR .'/helper_functions.php');

$smarty = new Smarty;

$smarty->debugging	 = $config["template"]["debugging"];
$smarty->caching      = $config["template"]["caching"];
$smarty->force_compile = $config["template"]["force_compile"];
$smarty->use_sub_dirs = $config["template"]["use_sub_dirs"];
$smarty->cache_lifetime = $config["template"]["cache_lifetime"];
$smarty->template_dir = IPS_DIR.'/templates';
$smarty->cache_dir = IPS_DIR . '/cache/templates/caching';
$smarty->compile_dir = IPS_DIR .'/cache/templates/compile';

session_name($config["appidentifier"]);
$sessionid = session_id();

if(empty($sessionid) || !isset($_SESSION["userdata"]))
{
  session_start();
}

if(!isset($_SESSION["userdata"]))
{
  PermissionManager::SetDefaultSession();
}

$system = array("currentversion"=>'0.0.1','versiontype'=>'dev');

PermissionManager::Prefetch($_SESSION['userdata']['id']);
