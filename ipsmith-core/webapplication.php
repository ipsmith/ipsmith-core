<?php
require ( dirname(__FILE__) .'/libs/global.php');

$req["module"] = "list";
$req["page"] = "index";
$req["displaytype"] = "index";
$req = parseRequest($_REQUEST);


//-- load pages
$includeBootstrap = IPS_DIR.'/modules/'.$req["module"].'/_bootstrap.php';
$includeFile = IPS_DIR.'/modules/'.$req["module"].'/'.$req["page"].'.php';

if(file_exists($includeBootstrap))
{
	include($includeBootstrap);
}

if(file_exists($includeFile))
{
	include($includeFile);
}

//--- always used data
$q = "SELECT * FROM locations ORDER BY ordernumber";
$res = mysql_query($q);
$globallocations = array();
while($row = mysql_fetch_assoc($res))
{
    $globallocations[] = $row;
}

$smarty->assign('globallocations',$globallocations);
$smarty->assign('currentTitle',null);
$smarty->assign('currentModule',$req["module"]);
$smarty->assign('currentPage',$req["page"]);
$smarty->assign('config',$config);


$smarty->display($req["displaytype"].'.tpl');
