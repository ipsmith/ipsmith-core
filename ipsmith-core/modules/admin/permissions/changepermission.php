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

$permissionid   = $_REQUEST["permission"];
$roleid         = $_REQUEST["role"];

if(is_numeric($permissionid) && is_numeric($roleid))
{
    $selectAssignmentsQuery = 'SELECT * FROM roles_permissions WHERE roles_id=:roleid AND permissions_id=:permissionid ;';
    $selectAssignmentsStmt  = $doctrineConnection->prepare($selectAssignmentsQuery);
    $selectAssignmentsStmt->bindValue('roleid',$roleid);
    $selectAssignmentsStmt->bindValue('permissionid',$permissionid);
    $selectAssignmentsStmt->execute();

    if($row = $selectAssignmentsStmt->fetch())
    {
        $modifyAssignmentsQuery = 'DELETE FROM roles_permissions WHERE roles_id=:roleid AND permissions_id=:permissionid;';
    }
    else
    {
        $modifyAssignmentsQuery = 'INSERT INTO roles_permissions (roles_id, permissions_id) VALUES (:roleid , :permissionid );';
    }

    $modifyAssignmentsStmt  = $doctrineConnection->prepare($modifyAssignmentsQuery);
    $modifyAssignmentsStmt->bindValue('roleid',$roleid);
    $modifyAssignmentsStmt->bindValue('permissionid',$permissionid);
    $modifyAssignmentsStmt->execute();
}

header('Location: '.$config['baseurl'].'/admin/permissions/index.html');
die();