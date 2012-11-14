<?php
$requestedlocationid = 1;
$catid = 1;

if(Isset($_REQUEST["locationid"]))
{
    $requestedlocationid = $_REQUEST["locationid"];
}
$smarty->assign("locationid",$requestedlocationid);




$q = "SELECT * FROM locations WHERE id= :id ";

$stmt = $doctrineConnection->prepare($q);
$stmt->bindValue("id", $requestedlocationid);
$stmt->execute();
if($row = $stmt->fetch())
{
    $catid = $row["preselectedcat"];
}

if(isset($_REQUEST["catid"]))
{
    $catid = $_REQUEST["catid"];
}


$smarty->assign("catid",$catid);

$currentcategories = array();
$q = "SELECT * FROM categories WHERE id in (SELECT catid FROM entries WHERE locationid= :locationid ) ORDER by ordernumber";
$stmt = $doctrineConnection->prepare($q);
$stmt->bindValue("locationid", $requestedlocationid);
$stmt->execute();
while($row = $stmt->fetch())
{
    $currentcategories[] = $row;
}



$entries = array();
$q = "SELECT * FROM entries WHERE locationid= :locationid AND catid= :catid";
$stmt = $doctrineConnection->prepare($q);
$stmt->bindValue("locationid", $requestedlocationid);
$stmt->bindValue("catid",$catid);
$stmt->execute();
while($row = $stmt->fetch())
{                                                                                                                                                                                                                                              
        $entries[] = $row;
}

$smarty->assign('currentcategories',$currentcategories);
$smarty->assign("entries",$entries);
