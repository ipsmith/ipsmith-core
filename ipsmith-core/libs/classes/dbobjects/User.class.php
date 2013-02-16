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

class User extends BaseObject
{
	public $row = array();
	public $tablename = "users";
	public $username = null;
	public $password = null;
	public $email = null;
	public $apikey = null;
	public $firstname = null;
	public $lastname = null;
	public $language = null;
	public $newpassword = null;
	public $configarray = array();

	public $config = null;

	public function Save()
	{
		$isupdate = $this->IsValid();
		AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));

		if($this->IsValid())
		{
			$saveQuery = "UPDATE users SET username=:username, email=:email, firstname=:firstname, lastname=:lastname, language=:language WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue("id", $this->id);
		}
		else
		{
			$saveQuery = "INSERT INTO users (username, email, firstname, lastname, language) VALUES (:username, :email, :firstname, :lastname, :language);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('username',$this->username);
		$saveStmt->bindValue('email',$this->email);
		$saveStmt->bindValue('firstname',$this->firstname);
		$saveStmt->bindValue('lastname',$this->lastname);
		$saveStmt->bindValue('language',$this->language);
		$saveStmt->execute();

		if($isupdate)
		{
			$this->id=Database::current()->lastInsertId();
		}


		if($this->newpassword!=null)
		{
			$this->UpdatePassword();
		}

		$this->SaveMetaData($this->tablename, $this->id,$isupdate);
		$this->LoadById($this->id);
		$this->newpassword = null;
	}

	public function PublishToSession()
	{
		session_destroy();
		session_start();
		$_SESSION["userdata"] = array();
		$_SESSION["userdata"] = $this->row;

		$this->PublishConfigToSession();
	}

	public function PublishConfigToSession()
	{
		$_SESSION["userdata"]["config"] = $this->configarray;
	}
	public function HashPassword($_password)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-password"=>$_password));
		return md5(sha1($_password));
	}

	public function UpdatePassword()
	{
		AuditHandler::FireEvent(__METHOD__,array("password"=>$this->newpassword));

		$updateUserPasswordQuery = "UPDATE users SET apikey=:apikey, password=:password, modifiedby=:modifiedby, modifiedat=CURRENT_TIMESTAMP WHERE id=:id";
          $updateUserPasswordStmt = Database::current()->prepare($updateUserPasswordQuery);
          $updateUserPasswordStmt->bindValue('password',$this->HashPassword($this->newpassword));
          $updateUserPasswordStmt->bindValue('apikey', GuidGenerator::Create('API'));
          $updateUserPasswordStmt->bindValue('modifiedby',$_SESSION["userdata"]["username"]);
          $updateUserPasswordStmt->bindValue('id',$this->id);
          $updateUserPasswordStmt->execute();
          $this->newpassword = null;
          $this->LoadById($this->id);
	}
	public function LoadAll()
	{
		$loadQuery = "SELECT id FROM users ORDER BY username";
		$loadStmt = Database::current()->prepare($loadQuery);
          $loadStmt->execute();

          $objects = array();
	    while($row = $loadStmt->fetch())
	    {
	    		$object = new User();
	    		$object->LoadById($row["id"]);
	    		$objects[] =  $object;
	    }
	    return $objects;
	}

	public function LoadById($_id)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id));

		$loadUserQuery = "SELECT * FROM users WHERE id=:id";
		$loadUserStmt = Database::current()->prepare($loadUserQuery);
          $loadUserStmt->bindValue('id',$_id);
          $loadUserStmt->execute();

	    if($userRow = $loadUserStmt->fetch())
	    {
	    		$this->LoadData($userRow);
	    }
	}

	public function LoadByUsername($_username)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-username"=>$_username));

		$loadUserQuery = "SELECT * FROM users WHERE username=:username";
		$loadUserStmt = Database::current()->prepare($loadUserQuery);
		$loadUserStmt->bindValue('username',$_username);
		$loadUserStmt->execute();

		if($userRow = $loadUserStmt->fetch())
		{
			$this->LoadData($userRow);
		}
	}

	public function LoadByUsernameAndPassword($_username,$_password)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-username"=>$_username,"param-password"=>$_password));

		$loadUserQuery = "SELECT * FROM users WHERE username=:username AND password=:password";
		$loadUserStmt = Database::current()->prepare($loadUserQuery);
		$loadUserStmt->bindValue('username',$_username);
		$loadUserStmt->bindValue('password',$this->HashPassword($_password));
		$loadUserStmt->execute();

		if($userRow = $loadUserStmt->fetch())
		{
			$this->LoadData($userRow);
		}
	}
	public static function GetByUsernameAndPassword($_username,$_password)
	{
		$object = new User();
		$object->LoadByUsernameAndPassword($_username,$_password);
		return $object;
	}
	public static function GetByUsername($_username)
	{
		$object = new User();
		$object->LoadByUsername($_username);
		return $object;
	}
	public static function GetById($_id)
	{
		$object = new User();
		$object->LoadById($_id);
		return $object;
	}
	public static function GetAll()
	{
		AuditHandler::FireEvent(__METHOD__,null);
		return self::LoadAll();
	}

	public static function IsFree($_username)
	{
		$user = self::GetByUsername($_username);
		return (!$user->isValid());
	}

	public function LoadData($row)
	{
		$settings = UserSetting::GetByUserId($row["id"]);

		parent::LoadMetaData($row);
		$this->username = $row["username"];
		$this->password = $row["password"];
		$this->email = $row["email"];
		$this->apikey = $row["apikey"];
		$this->firstname = $row["firstname"];
		$this->lastname = $row["lastname"];
		$this->language = $row["language"];
		$this->config = $settings;

		foreach($settings as $setting)
		{
			$this->configarray[$setting->settingsname] = $setting->settingsvalue;
		}

		$this->row = $row;
	}
}