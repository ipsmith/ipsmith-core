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
$selectUsersQuery = 'SELECT * FROM users ORDER BY username';
$selectUsersStmt = $doctrineConnection->prepare($selectUsersQuery);
$selectUsersStmt->execute();

$selectRolesQuery = 'SELECT rolename, role_humanname FROM VIEW_users_roles WHERE userid= :userid ORDER BY rolename';
$selectRolesStmt = $doctrineConnection->prepare($selectRolesQuery);

while ($selectUsersRow = $selectUsersStmt->fetch())
{
    $selectUsersWorkerRow = $selectUsersRow;
    $selectUsersWorkerRow["roles"] = null;

    $selectRolesStmt->bindValue('userid',$selectUsersRow["id"]);
    $selectRolesStmt->execute();

    $i = 0;
    while($selectRolesRow = $selectRolesStmt->fetch())
    {
        if($i>0) 
        {
            $selectUsersWorkerRow["roles"].=", ";
        }

        if(trim($selectRolesRow["role_humanname"])=="")
        {
             $selectUsersWorkerRow["roles"].=$selectRolesRow["rolename"];
        }
        else
        {
            $selectUsersWorkerRow["roles"].= $selectRolesRow["role_humanname"];
        }
        $i++;
    }
    $selectUsersWorkerRow["linkid"] = $selectUsersWorkerRow["id"];
    $entries[] = $selectUsersWorkerRow;
}
$smarty->assign('entries',$entries);