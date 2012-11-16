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
    $q = "SELECT * FROM users WHERE username= :username AND password = :password ";

    $stmt = $doctrineConnection->prepare($q);

    $stmt->bindValue('username',$_REQUEST["username"]);
    $stmt->bindValue('password',userHashPassword($_REQUEST["password"]));

    $stmt->execute();

    $globallocations = array();

    if($row = $stmt->fetch())
    {
        $_SESSION["userdata"] = $row;

        //-- We need to validate settings.

        $dbUserSettings = array();
        $q = "SELECT * FROM user_settings WHERE userid= :userid";
        $settingsStmt = $doctrineConnection->prepare($q);

        $settingsStmt->bindValue('userid',$row["id"]);

        $settingsStmt->execute();
        $_SESSION["userdata"]["config"] = null;
        while($settingsRow = $stmt->fetch())
        {

            $dbUserSettings[$settingsRow["settingsname"]] = $settingsRow["settingsvalue"];
        }

        foreach($defaultconfig["defaultsettings"] as $key => $value)
        {
            if(!isset($dbUserSettings[$key]))
            {
                $query = "INSERT INTO user_settings (userid,settingsname,settingsvalue) VALUES ( :userid, :settingsname, :settingsvalue );";
                $settingsUpdaterStmt = $doctrineConnection->prepare($q);

                $settingsUpdaterStmt->bindValue('userid',$row["userid"]);
                $settingsUpdaterStmt->bindValue('settingsname', $key);
                $settingsUpdaterStmt->bindValue('settingsvalue', $value);
                $settingsUpdaterStmt->execute();

                $dbUserSettings[$key] = $value;
            }
        }

        $_SESSION["userdata"]["config"] = $dbUserSettings;
        @header("Location: ".$config["baseurl"]);
    }
    else
    {
        //-- todo: handle error
    }
}
