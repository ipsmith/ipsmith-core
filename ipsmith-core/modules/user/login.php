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

if(isset($_REQUEST["submit"]))
{
    $LogHandler->Log("USER-LOGIN ", IPSMITH_INFO, array('request'=>$_REQUEST));
    $q = "SELECT * FROM users WHERE username= :username AND password = :password ";

    $stmt = $doctrineConnection->prepare($q);

    $stmt->bindValue('username',$_REQUEST["username"]);
    $stmt->bindValue('password',userHashPassword($_REQUEST["password"]));

    $stmt->execute();

    $globallocations = array();

    if($userRow = $stmt->fetch())
    {
        session_destroy();
        session_start();
        $_SESSION["userdata"] = $userRow;
        $LogHandler->Log("USER-LOGIN-SUCCESSFULL ", IPSMITH_INFO, array('request'=>$_REQUEST, 'data-retrieved'=>$userRow));

        if(PermissionManager::HasPermission($userRow["id"],'can_login') && PermissionManager::HasPermission($userRow["id"],'can_login_web'))
        {

            $dbUserSettings = array();
            $selectSettingsQuery = "SELECT * FROM user_settings WHERE userid= :userid";
            $settingsStmt = $doctrineConnection->prepare($selectSettingsQuery);

            $settingsStmt->bindValue('userid',$userRow["id"]);

            $settingsStmt->execute();
            $_SESSION["userdata"]["config"] = null;
            while($settingsRow = $settingsStmt->fetch())
            {

                $dbUserSettings[$settingsRow["settingsname"]] = $settingsRow["settingsvalue"];
            }

            $LogHandler->Log("USER-LOGIN Fetched Settings", IPSMITH_INFO, array('request'=>$_REQUEST,  'user-data'=>$userRow,  'data-built'=>$dbUserSettings));

            foreach($defaultconfig["defaultsettings"] as $key => $value)
            {
                if(!isset($dbUserSettings[$key]))
                {
                    $settingsUpdaterQuery = "INSERT INTO user_settings (userid,settingsname,settingsvalue,createdby) VALUES ( :userid, :settingsname, :settingsvalue, :createdby );";
                    $settingsUpdaterStmt = $doctrineConnection->prepare($settingsUpdaterQuery);

                    $settingsUpdaterStmt->bindValue('userid',$userRow["id"]);
                    $settingsUpdaterStmt->bindValue('settingsname', $key);
                    $settingsUpdaterStmt->bindValue('settingsvalue', $value);
                    $settingsUpdaterStmt->bindValue('createdby', 'loginprocedure');
                    $settingsUpdaterStmt->execute();

                    $dbUserSettings[$key] = $value;
                }
            }

            $LogHandler->Log("USER-LOGIN Pumping Settings", IPSMITH_INFO, array('request'=>$_REQUEST,  'user-data'=>$userRow,  'data-pump'=>$dbUserSettings));
            $_SESSION["userdata"]["config"] = $dbUserSettings;
            $LogHandler->Log("USER-LOGIN New Session-data", IPSMITH_INFO, array('user-data'=>$userRow,  'data-session'=>$_SESSION));
            @header("Location: ".$config["baseurl"]."/?from=login");
        }
        else
        {
            session_destroy();
            PermissionManager::SetDefaultSession();
            $LogHandler->Log("USER-LOGIN-FAILED missing permissions", IPSMITH_INFO, array('request'=>$_REQUEST, 'data-retrieved'=>$userRow));
            PumpMessage('error','Sie können sich momentan nicht anmelden.');
        }
    }
    else
    {
        $LogHandler->Log("USER-LOGIN-FAILED wrong credentials", IPSMITH_INFO, array('request'=>$_REQUEST));
        PumpMessage('error','Sie haben die Falschen Zugangsdaten angegeben.');
    }
}

SetTitle('Anmelden');