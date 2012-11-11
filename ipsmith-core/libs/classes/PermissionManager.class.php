<?php
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
