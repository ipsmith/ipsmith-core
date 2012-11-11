<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$entries= array();
$q = "SELECT id,username,email FROM users ";

    if($_REQUEST["search"]!="undefined")
    {
        $add ="WHERE username like '%%%s%%' OR firstname like '%%%s%%' OR lastname like '%%%s%%' OR email like '%%%s%%'";
        $search = mysql_real_escape_string($_REQUEST["search"]);
        $q.= sprintf($add, $search, $search, $search, $search);
    }

    $q.=" ORDER BY username";
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
        $smarty->assign('userid',$r["id"]);

    $r["button"] = $smarty->fetch('admin/users/_usereditbutton.tpl');
    $entries[] = $r;
}
echo json_encode($entries,JSON_FORCE_OBJECT);
exit;
