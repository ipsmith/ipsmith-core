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
	$LogHandler->Log("Loading ".$includeBoootstrap,IPSMITH_DEBUG);
	include($includeBootstrap);
}

if(file_exists($includeFile))
{
	$LogHandler->Log("Loading ".$includeFile,IPSMITH_DEBUG);
	include($includeFile);
}

//--- always used data
$q = "SELECT * FROMlocations ORDER BY ordernumber";

$stmt = $doctrineConnection->query($q);
$globallocations = array();
while($row = $stmt->fetch())
{
	$globallocations[] = $row;
}
/*$res = mysql_query($q);
while($row = mysql_fetch_assoc($res))
{
    
}*/

$smarty->assign('globallocations',$globallocations);
$smarty->assign('currentTitle',null);
$smarty->assign('currentModule',$req["module"]);
$smarty->assign('currentPage',$req["page"]);
$smarty->assign('config',$config);


$smarty->display($req["displaytype"].'.tpl');
