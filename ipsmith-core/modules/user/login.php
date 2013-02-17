<?php
/**
 * Project:	 IPSmith - Free ip address managing tool
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

	$user = User::GetByUsernameAndPassword($_REQUEST["username"],$_REQUEST["password"]);
	if($user->IsValid() && (PermissionManager::HasPermission($user->id,'can_login') && PermissionManager::HasPermission($user->id,'can_login_web')))
	{
		$user->PublishToSession();
		$user->PublishConfigToSession();

		foreach($defaultconfig["defaultsettings"] as $key => $value)
		{
			if(!isset($user->configarray[$key]))
			{
				$userSetting = new UserSetting();
				$userSetting->settingsname = $key;
				$userSetting->settingsvalue = $value;
				$userSetting->userid = $user->id;
				$userSetting->Save();

				$dbUserSettings[$key] = $value;
				$_SESSION["userdata"]["config"][$key] = $value;
			}
		}

		$LogHandler->Log("USER-LOGIN New Session-data", IPSMITH_INFO, array('user-data'=>$userRow,  'data-session'=>$_SESSION));
		@header("Location: ".$config["baseurl"]."/?from=login");
	}
	else
	{
		if(!$user->IsValid())
		{
			$LogHandler->Log("USER-LOGIN-FAILED wrong credentials", IPSMITH_INFO, array('request'=>$_REQUEST));
			PumpMessage('error','Sie haben die Falschen Zugangsdaten angegeben.');
		}
		else
		{
			$LogHandler->Log("USER-LOGIN-FAILED missing permissions", IPSMITH_INFO, array('request'=>$_REQUEST));
			PumpMessage('error','Sie können sich momentan nicht anmelden.');
		}

		session_destroy();
		PermissionManager::SetDefaultSession();
	}
}

SetTitle('Anmelden');