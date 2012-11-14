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
 * or see http://www.ipsmith.org/docs/license
 *
 * For questions, help, comments, discussion, etc., please join the
 * IPSmith mailing list. Go to http://www.ipsmith.org/lists 
 *
 **/    

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
