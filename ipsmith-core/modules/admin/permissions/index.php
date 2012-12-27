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

$permissions    = array();
$roles          = array();
$assigned       = array();

$selectAssignmentsQuery = 'SELECT * FROM VIEW_roles_permissions ORDER BY permissionname';
$selectAssignmentsStmt  = $doctrineConnection->prepare($selectAssignmentsQuery);
$selectAssignmentsStmt->execute();

while ($row = $selectAssignmentsStmt->fetch())
{
    if(!isset($assigned[$row["rolename"]]))
    {
            $assigned[$row["rolename"]] = array();
    }

    if(!isset($assigned[$row["rolename"]][$row["permissionname"]]))
    {
            $assigned[$row["rolename"]][$row["permissionname"]] = 1;
    }
}


$selectPermissionsQuery = 'SELECT * FROM permissions ORDER BY permissionname';
$selectPermissionsStmt  = $doctrineConnection->prepare($selectPermissionsQuery);
$selectPermissionsStmt->execute();

while ($row = $selectPermissionsStmt->fetch())
{
    if(!in_array($row['permissionname'], $permissions))
    {
        $permissions[] = array('name'=>$row["permissionname"],'id'=>$row["id"]);
    }
}


$selectRolesQuery = 'SELECT * FROM roles ORDER BY name';
$selectRolesStmt  = $doctrineConnection->prepare($selectRolesQuery);
$selectRolesStmt->execute();

while ($row = $selectRolesStmt->fetch())
{
    if(!in_array($row['name'], $roles))
    {
        $roles[] = $row;
    }
}

$smarty->assign('permissions',$permissions);
$smarty->assign('assigned',$assigned);
$smarty->assign('roles',$roles);