<?php
$requestedlocationid = 1;
$catid = 1;

if(Isset($_REQUEST["locationid"]))
{
    $requestedlocationid = $_REQUEST["locationid"];
}
$smarty->assign("locationid",$requestedlocationid);


$q = "SELECT * FROM locations where id=%d";
$q = sprintf($q,mysql_real_escape_string($requestedlocationid));
$res = mysql_query($q);
if($row = mysql_fetch_assoc($res))
{
    $catid = $row["preselectedcat"];
}
if(isset($_REQUEST["catid"]))
{
    $catid = $_REQUEST["catid"];
}


$smarty->assign("catid",$catid);

$currentcategories = array();
$q = "SELECT * FROM categories WHERE id in (SELECT catid FROM entries WHERE locationid=%d) ORDER by ordernumber";
$q = sprintf($q,mysql_real_escape_string($requestedlocationid));

$res = mysql_query($q);
while($row = mysql_fetch_assoc($res))
{
    $currentcategories[] = $row;
}


$entries = array();
$q = "SELECT * FROM entries WHERE locationid=%d AND catid=%d";
$q = sprintf($q,mysql_real_escape_string($requestedlocationid),mysql_real_escape_string($catid));
$res = mysql_query($q);
while($row = mysql_fetch_array($res))
{
    $entries[] = $row;
}

$smarty->assign('currentcategories',$currentcategories);
$smarty->assign("entries",$entries);
