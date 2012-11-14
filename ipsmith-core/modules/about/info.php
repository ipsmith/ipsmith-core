<?php



$versions = (array)parseApiRequest("version/latest.json");


$latest = null;

foreach($versions as $ver)
{
    if($ver["versiontype"] == $system["versiontype"])
    {
        $latest = $ver;
    }
}

$system["latestversion"] = $latest;

$numberCurrent = str_replace(".",null,$system["currentversion"]);
$numberLatest = str_replace(".",null,$latest["versionnumber"]);

if($numberLatest > $numberCurrent)
{
    $system["newerversion"] = 1;
}
else
{
    $system["newerversion"] = 0;
}


$q = "SELECT count(id) as count FROM entries";
$stmt = $doctrineConnection->query($q);
$globallocations = array();
if($row = $stmt->fetch())
{
    $system["entries"] = $row["count"];
}

$smarty->assign('system',$system);
