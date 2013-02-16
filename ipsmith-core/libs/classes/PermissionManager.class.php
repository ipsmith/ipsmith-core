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

    public static function SetDefaultSession()
    {
        global $config;

        $_SESSION["userdata"] = array();
        $_SESSION["userdata"]["id"] = 0;
        $_SESSION["userdata"]["username"] = "guest";
        $_SESSION["userdata"]["language"] = "de";
        $_SESSION["userdata"]["config"] = $config["defaultsettings"];
    }
    public static function GetMissingRoles($_module,$_page)
    {
        global $doctrineConnection;


        $getRoleDataQuery = "SELECT * FROM roles WHERE name=:name;";
        $getRoleDataStmt = Database::current()->prepare($getRoleDataQuery);

        $getRequiredRolesQuery = "SELECT * FROM permissions_matrix WHERE matrixtype=:matrixtype AND module=:module AND page=:page ;";

        $getRequiredRolesStmt = Database::current()->prepare($getRequiredRolesQuery);

        $getRequiredRolesStmt->bindValue('matrixtype', 'required');
        $getRequiredRolesStmt->bindValue('module',$_module);
        $getRequiredRolesStmt->bindValue('page',$_page);

        $getRequiredRolesStmt->execute();

        $missingRoles = array();

        while($requiredRolesRow = $getRequiredRolesStmt->fetch())
        {
            if(!PermissionManager::CurrentUserHasRole($requiredRolesRow['role']))
            {
                if(!in_array($requiredRolesRow['role'],$missingRoles))
                {
                    $getRoleDataStmt->bindValue('name',$requiredRolesRow['role']);
                    $getRoleDataStmt->execute();

                    if($rowDataRow = $getRoleDataStmt->fetch())
                    {
                        $missingRoles[] =  $rowDataRow;
                    }
                }
            }
        }

        return $missingRoles;
    }

    public static function RequirePermissions($_module,$_page)
    {
        global $doctrineConnection;

        if(
            ($_module!='error' && $_page!='catch') &&
            ($_module!='user' && $_page!='login') &&
            ($_module!='user' && $_page!='logout')  &&
            ($_module!='user' && $_page!='missingpermissions')
            )
        {
            return true;
        }

        return false;
    }

    public static function CurrentUserHasRole($rolename)
    {
        PermissionManager::Prefetch($_SESSION['userdata']['id']);
        return (isset($_SESSION['useraccess']['roles'][$rolename]) || isset($_SESSION['useraccess']['roles']['admin']) || isset($_SESSION['useraccess']['permissions']['can_do_everything']));
    }

    public static function CurrentUserHasPermission($permissionname)
    {
        PermissionManager::Prefetch($_SESSION['userdata']['id']);
        return (isset($_SESSION['useraccess']['permissions'][$permissionname]) || isset($_SESSION['useraccess']['permissions']['can_do_everything']) );
    }

	public static function HasPermission($userid,$permissionname)
    {
        global $doctrineConnection;

		$q = "SELECT * FROM VIEW_users_permissions WHERE userid= :userid AND (permissionname= :permissionname OR  permissionname='can_do_everything');";

        $stmt = Database::current()->prepare($q);

        $stmt->bindValue('userid',$userid);
        $stmt->bindValue('permissionname',$permissionname);

        $stmt->execute();


        if($row = $stmt->fetch())
        {
            return true;
        }

		return false;
	}


    public static function Prefetch($userid)
    {
        global $doctrineConnection, $LogHandler;

        if(!isset($_SESSION['useraccess']['lastfetch']))
        {
            $_SESSION['useraccess']['lastfetch'] = time();
        }

        if($_SESSION['useraccess']['lastfetch']<time()-120 ||
            !isset($_SESSION['useraccess']['userid']) ||
             $_SESSION['useraccess']['userid']!=$_SESSION['userdata']['id'])
        {
            $LogHandler->Log('prefetching permissions', IPSMITH_DEBUG);

            $_SESSION['useraccess']                 = array();
            $_SESSION['useraccess']['roles']        = array();
            $_SESSION['useraccess']['permissions']  = array();
            $_SESSION['useraccess']['lastfetch']    = time();
            $_SESSION['useraccess']['userid']       =$_SESSION['userdata']['id'];

            $selectUserAccessQuery = 'SELECT * FROM VIEW_users_permissions WHERE userid=:userid;';
            $selectUserAccessStmt = Database::current()->prepare($selectUserAccessQuery);
            $selectUserAccessStmt->bindValue('userid',$userid);

            $selectUserAccessStmt->execute();

            while($row = $selectUserAccessStmt->fetch())
            {
                if(!isset($_SESSION['useraccess']['roles'][$row['rolename']]))
                {
                    $_SESSION['useraccess']['roles'][$row['rolename']] = 1;
                }

                if(!isset($_SESSION['useraccess']['permissions'][$row['permissionname']]))
                {
                    $_SESSION['useraccess']['permissions'][$row['permissionname']] = 1;
                }
            }
        }
    }

	public static function HasRole($userid,$rolename)
    {
        global $doctrineConnection;

        $q = 'SELECT * FROM VIEW_users_permissions WHERE userid= :userid and rolename= :rolename';
        $stmt = Database::current()->prepare($q);

        $stmt->bindValue('userid',$userid);
        $stmt->bindValue('rolename',$rolename);

        $stmt->execute();


        if($row = $stmt->fetch())
        {
            return true;
        }

        return false;
	}
}
