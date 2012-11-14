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

$stmt = $doctrineConnection->prepare($q);

$stmt->execute();

$globallocations = array();

while ($row = $stmt->fetch())
{
    $r = $row;
    $r["roles"] = null;;

    $q = "SELECT rolename FROM VIEW_users_roles WHERE userid= :userid ORDER BY rolename";
    
    $stmt2 = $doctrineConnection->prepare($q);

    $stmt2->bindValue('userid',$row["id"]);

    $stmt2->execute();

    $globallocations = array();

    $i = 0;
    while($innerrow = $stmt2->fetch())
    {

        if($i>0) $r["roles"].=", ";
        $r["roles"].= $innerrow["rolename"];
        $i++;
    }
    $r["linkid"] = $r["id"];
    $entries[] = $r;
}
$smarty->assign('entries',$entries);
