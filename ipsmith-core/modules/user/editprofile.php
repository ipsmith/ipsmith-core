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

$selectLogsQuery = 'SELECT * FROM tablelog WHERE tablename=:tablename '
                 . 'AND tableid=:tableid ORDER BY id DESC;';

$selectLogsStmt = $doctrineConnection->prepare($selectLogsQuery);
$selectLogsStmt->bindValue('tablename','users');
$selectLogsStmt->bindValue('tableid',$_SESSION["userdata"]["id"]);
$selectLogsStmt->execute();

$logentries = array();
while($row = $selectLogsStmt->fetch())
{
    $logentries[] = $row;
}

$smarty->assign('logentries',$logentries);


if(isset($_POST["submit"]))
{
    $logQuery = 'INSERT INTO tablelog (tablename, tableid, username, action) '
              . 'VALUES (:tablename, :tableid, :username, :action);';

    $logStmt = $doctrineConnection->prepare($logQuery);
    $logStmt->bindValue('tablename','users');
    $logStmt->bindValue('tableid',$_SESSION["userdata"]["id"]);
    $logStmt->bindValue('username',$_SESSION['userdata']['username']);

    //-- update user data
    
    $updateUserQuery = "UPDATE users SET email=:email, firstname=:firstname, lastname=:lastname, modifiedby=:modifiedby, modifiedat=CURRENT_TIMESTAMP WHERE id=:id";
    $updateUserStmt = $doctrineConnection->prepare($updateUserQuery);
    $updateUserStmt->bindValue('email',$_POST["user_email"]);
    $updateUserStmt->bindValue('firstname',$_POST["user_firstname"]);
    $updateUserStmt->bindValue('lastname',$_POST["user_lastname"]);
    $updateUserStmt->bindValue('modifiedby',$_SESSION["userdata"]["username"]);
    $updateUserStmt->bindValue('id',$_SESSION["userdata"]["id"]);
    $updateUserStmt->execute();
    PumpMessage('success',"Eintrag erfolgreich bearbeitet.");
    $logStmt->bindValue('action','Entry #'.$_SESSION["userdata"]["id"].' updated');
    $logStmt->execute();


    //-- update userpassword and apikey
    
    if(!is_empty($_POST["user_password1"]) && !is_empty($_POST["user_password2"]))
    {
        if(md5($_POST["user_password1"])!=md5($_POST["user_password2"]))
        {
            PumpMessage('error','Kennwort sollte geändert werden, allerdings stimmen die Kennwörter nicht überein.');
        }
        else
        {
            if(userHashPassword($_POST["user_password1"])==$_SESSION["userdata"]["password"])
            {
                 PumpMessage('error','Kennwort sollte geändert werden, allerdings waren das neue Kennwort und das vorhandene Kennwort identisch.');       
            }
            else
            {
                $newapi = GuidGenerator::Create('API');

                $updateUserPasswordQuery = "UPDATE users SET apikey=:apikey, password=:password, modifiedby=:modifiedby, modifiedat=CURRENT_TIMESTAMP WHERE id=:id";
                $updateUserPasswordStmt = $doctrineConnection->prepare($updateUserPasswordQuery);
                $updateUserPasswordStmt->bindValue('password',userHashPassword($_POST["user_password1"]));
                $updateUserPasswordStmt->bindValue('apikey', $newapi);
                $updateUserPasswordStmt->bindValue('modifiedby',$_SESSION["userdata"]["username"]);
                $updateUserPasswordStmt->bindValue('id',$_SESSION["userdata"]["id"]);
                $updateUserPasswordStmt->execute();
                PumpMessage('success',"Kennwort wurde neu gesetzt.");
                PumpMessage('warning',"API-Key wurde neu gesetzt.");
                $logStmt->bindValue('action','Entry #'.$_SESSION["userdata"]["id"].' Kennwort und API-Key aktualisiert.');
                $logStmt->execute();
            }
        }
    }
    else if(!is_empty($_POST["user_password1"]) || !is_empty($_POST["user_password2"]))
    {
        if(is_empty($_POST["user_password1"]))
        {
             PumpMessage('error','Kennwort sollte geändert werden, allerdings war das neue Kennwort nicht angegeben.');
        }
        if(is_empty($_POST["user_password2"]))
        {
             PumpMessage('error','Kennwort sollte geändert werden, allerdings wurde das Kennwort nicht wiederholt.');
        }
    }

    //-- update user settings
    
    $newUserSettings = array();
    $newUserSettings[] = array('settingsname'=>'repeatheaders','settingsvalue'=>$_POST["usersettings_repeatheaders"]);
    $newUserSettings[] = array('settingsname'=>'usehumanname','settingsvalue'=>$_POST["usersettings_usehumanname"]);
    $newUserSettings[] = array('settingsname'=>'displayids','settingsvalue'=>$_POST["usersettings_displayids"]);

    $updateSettingQuery = "UPDATE user_settings SET settingsvalue=:settingsvalue, modifiedby=:username, modifiedat=CURRENT_TIMESTAMP WHERE settingsname=:settingsname and userid=:id";
    $updateSettingStmt = $doctrineConnection->prepare($updateSettingQuery);

    $updateSettingStmt->bindValue('id',$_SESSION["userdata"]["id"]);
    $updateSettingStmt->bindValue('username',$_SESSION["userdata"]["username"]);

    foreach($newUserSettings as $newsetting)
    {
        $updateSettingStmt->bindValue('settingsname',$newsetting["settingsname"]);
        $updateSettingStmt->bindValue('settingsvalue',$newsetting["settingsvalue"]);
        $updateSettingStmt->execute();
    }

    //-- read user data and publish them to current session
    
    $updateSessionUserdataQuery = "SELECT * FROM users WHERE id=:id";
    $updateSessionUserdataStmt = $doctrineConnection->prepare($updateSessionUserdataQuery);
    $updateSessionUserdataStmt->bindValue('id',$_SESSION["userdata"]["id"]);
    $updateSessionUserdataStmt->execute();

    if($userRow = $updateSessionUserdataStmt->fetch())
    {
        $_SESSION["userdata"] = $userRow;
    }

    //-- read user settings and publish them to current session
    
    $selectSettingsQuery = "SELECT * FROM user_settings WHERE userid= :userid";
    $settingsStmt = $doctrineConnection->prepare($selectSettingsQuery);

    $settingsStmt->bindValue('userid',$_SESSION["userdata"]["id"]);
    $settingsStmt->execute();

    $_SESSION["userdata"]["config"] = array();
    while($settingsRow = $settingsStmt->fetch())
    {
        $_SESSION["userdata"]["config"][$settingsRow["settingsname"]] = $settingsRow["settingsvalue"];
    }

}

$smarty->assign('currententry',$_SESSION["userdata"]);