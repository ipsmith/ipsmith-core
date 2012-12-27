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
	echo  "Please run \"php " .$argv[0]." permissionname\"\n";
	exit(1);
}
else
{
	include(dirname(__FILE__).'/../libs/global.php');

	$checkPermissionExistsQuery = "SELECT * FROM permissions WHERE permissionname=:permissionname";
	$checkPermissionExistsStmt  = $doctrineConnection->prepare($checkPermissionExistsQuery);

	$checkPermissionExistsStmt->bindValue('permissionname',$argv[1]);

	$checkPermissionExistsStmt->execute();

	if($row = $checkPermissionExistsStmt->fetch())
	{
		echo "Permission \"".$argv[1]."\" already exists.\n";
		exit(1);
	}
	else
	{
		$createPermissionQuery = "INSERT INTO permissions (permissionname, createdby) VALUES (:permissionname, :createdby);";
		$createPermissionStmt  = $doctrineConnection->prepare($createPermissionQuery);

		$createPermissionStmt->bindValue('permissionname',$argv[1]);
		$createPermissionStmt->bindValue('createdby','commandline');

		$createPermissionStmt->execute();

		echo "Permission \"".$argv[1]."\" created.\n";
		exit(0);
	}
}