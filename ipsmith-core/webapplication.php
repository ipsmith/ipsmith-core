<?php
/**
 * Project:     IPSmith - Free ip address managing tool
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

try
{
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

$globallocations = array();

//--- always used data
$q = "SELECT * FROM locations ORDER BY ordernumber";

$stmt = $doctrineConnection->query($q);
while($row = $stmt->fetch())
{
	$globallocations[] = $row;
}
$smarty->assign('globallocations',$globallocations);
$smarty->assign('currentTitle',null);
$smarty->assign('currentModule',$req["module"]);
$smarty->assign('currentPage',$req["page"]);
$smarty->assign('config',$config);


$smarty->display($req["displaytype"].'.tpl');
}
catch (Exception $e)
{
    $_SESSION["lasterror"] = $e;
    header("Location: ".$config["baseurl"]."/error/catch.html");
}
