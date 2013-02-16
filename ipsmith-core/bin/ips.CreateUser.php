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

if(!isset($argv[1]) || !isset($argv[2]))
{
	echo  "Please run \"php " .$argv[0]." username password\"\n";
	exit(1);
}
else
{
	include(dirname(__FILE__).'/../libs/global.php');

	$checkUserExistsQuery = "SELECT * FROM users WHERE username=:username";
	$checkUserExistsStmt  = $doctrineConnection->prepare($checkUserExistsQuery);

	$checkUserExistsStmt->bindValue('username',$argv[1]);

	$checkUserExistsStmt->execute();

	if($row = $checkUserExistsStmt->fetch())
	{
		echo "User \"".$argv[1]."\" already exists.\n";
		exit(1);
	}
	else
	{
		$createUserQuery = "INSERT INTO users (username, password, apikey, createdby) VALUES (:username, :password, :apikey, :createdby);";
		$createUserStmt  = $doctrineConnection->prepare($createUserQuery);

		$createUserStmt->bindValue('username',$argv[1]);
		$createUserStmt->bindValue('password',userHashPassword($argv[2]));
		$createUserStmt->bindValue('apikey', GuidGenerator::Create('api') );
		$createUserStmt->bindValue('createdby','commandline');

		$createUserStmt->execute();

		echo "User \"".$argv[1]."\" without any roles created.\n";
		exit(0);
	}
}