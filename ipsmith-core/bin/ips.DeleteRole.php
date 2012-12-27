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

if(!isset($argv[1]))
{
	echo  "Please run \"php " .$argv[0]." rolename\"\n";
	exit(1);
}
else
{
	include(dirname(__FILE__).'/../libs/global.php');

	$checkRoleExistsQuery = "SELECT * FROM roles WHERE name=:name";
	$checkRoleExistsStmt  = $doctrineConnection->prepare($checkRoleExistsQuery);

	$checkRoleExistsStmt->bindValue('name',$argv[1]);

	$checkRoleExistsStmt->execute();

	if($row = $checkRoleExistsStmt->fetch())
	{
		$deleteRoleAssignmentQuery = "DELETE FROM roles_permissions WHERE roles_id=:roleid;";
		$deleteRoleAssignmentStmt  = $doctrineConnection->prepare($deleteRoleAssignmentQuery);

		$deleteRoleAssignmentStmt->bindValue('roleid',$row["id"]);
		$deleteRoleAssignmentStmt->execute();

		echo "Permissions assignments to Role \"".$argv[1]."\" deleted.\n";

		$deleteRoleUserAssignmentQuery = "DELETE FROM users_roles WHERE roles_id=:roleid;";
		$deleteRoleUserAssignmentStmt  = $doctrineConnection->prepare($deleteRoleUserAssignmentQuery);

		$deleteRoleUserAssignmentStmt->bindValue('roleid',$row["id"]);
		$deleteRoleUserAssignmentStmt->execute();

		echo "User assignments to Role \"".$argv[1]."\" deleted.\n";

		$deleteRoleQuery = "DELETE FROM roles WHERE name=:name;";
		$deleteRoleStmt  = $doctrineConnection->prepare($deleteRoleQuery);

		$deleteRoleStmt->bindValue('name',$argv[1]);
		$deleteRoleStmt->execute();

		echo "Role \"".$argv[1]."\" deleted.\n";
		exit(0);
	}
	else
	{
		echo "Role \"".$argv[1]."\" already deleted or never exists.\n";
		exit(1);
	}
}