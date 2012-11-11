<?php
$entries= array();
$q = "SELECT id,username,email FROM users ORDER BY username";
$result = mysql_query($q);
while($row = mysql_fetch_assoc($result))
{
    $r = $row;
    $r["roles"] = null;;

    $q = "SELECT rolename FROM VIEW_users_roles WHERE userid=%d ORDER BY rolename";
    $q = sprintf($q,$row["id"]);
    $res = mysql_query($q);

    $i = 0;
    while($ro = mysql_fetch_assoc($res))
    {
        if($i>0) $r["roles"].=", ";
        $r["roles"].= $ro["rolename"];

        $i++;
    }
    $r["linkid"] = $r["id"];
    $entries[] = $r;
}
$smarty->assign('jsonentries',json_encode($entries,JSON_FORCE_OBJECT));
$smarty->assign('entries',$entries);
