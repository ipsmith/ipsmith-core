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
 *
 * For questions, help, comments, discussion, etc., please join the
 * IPSmith mailing list. Go to http://www.ipsmith.org/lists 
 *
 **/

class PermissionManager
{
	public static function HasPermission($userid,$permissionname)
	{
		$q = "SELECT * FROM VIEW_users_permissions WHERE userid=%i and permissionname='%s'";
		$q = sprintf($q,$userid,$permissionname);
		$result = mysql_query($q);
		if($row = mysql_fetch_assoc($result))
		(
			// TODO: add logging
			return true;
		)

		// TODO: add logging
		return false;
	}

	public static function HasRole($userid,$rolename)
	{
                $q = "SELECT * FROM VIEW_users_permissions WHERE userid=%i and rolename='%s'";
                $q = sprintf($q,$userid,$rolename);
                $result = mysql_query($q);
                if($row = mysql_fetch_assoc($result))
                (
                        // TODO: add logging
                        return true;
                )

                // TODO: add logging
                return false;

	}
}
