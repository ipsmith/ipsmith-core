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

class UserSetting extends BaseObject
{
	public $tablename = "user_settings";

	public $userid 			= 0;
	public $settingsname  	= null;
	public $settingsvalue  	= null;

	public function Save()
	{
		$isupdate = $this->IsValid();
		AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));

		if($this->IsValid())
		{
			$saveQuery = "UPDATE ".$this->tablename." SET settingsvalue=:settingsvalue, settingsname=:settingsname, userid=:userid WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			$saveQuery = "INSERT INTO ".$this->tablename." (settingsvalue, settingsname, userid) VALUES (:settingsvalue, :settingsname, :userid);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('settingsname',$this->settingsname);
		$saveStmt->bindValue('settingsvalue',$this->settingsvalue);
		$saveStmt->bindValue('userid',$this->userid);
		$saveStmt->execute();

		if(!$isupdate)
		{
			$this->id = Database::current()->lastInsertId();
		}

        $this->SaveMetaData($this->tablename, $this->id,$isupdate);
		$this->LoadById($this->id);
	}

	public function LoadData($row)
	{
		//-- MetaData
		parent::LoadMetaData($row);

		//-- EntryData
		$this->userid= $row["userid"];
		$this->settingsname= $row["settingsname"];
		$this->settingsvalue = $row["settingsvalue"];
	}

	public function LoadById($_id)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id));

		$loadQuery = "SELECT * FROM ".$this->tablename." WHERE id=:id";
		$loadStmt = Database::current()->prepare($loadQuery);
          $loadStmt->bindValue('id',$_id);
          $loadStmt->execute();

	    if($row = $loadStmt->fetch())
	    {
	    		$this->LoadData($row);
	    }
	}

	public function LoadByUserIdAndName($_userid, $_settingsname)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-userid"=>$_userid, "param-settingsname"=>$_settingsname));

		$loadQuery = "SELECT * FROM ".$this->tablename." WHERE userid=:userid AND settingsname=:settingsname";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->bindValue('userid',$_userid);
		$loadStmt->bindValue('settingsname',$_settingsname);
		$loadStmt->execute();

	    if($row = $loadStmt->fetch())
	    {
	    		$this->LoadData($row);
	    }
	}

	public static function GetByUserId($_userid)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-userid"=>$_userid));

		$loadQuery = "SELECT id FROM usersettings WHERE userid=:userid";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->bindValue('userid',$_userid);
		$loadStmt->execute();
		$objects = array();
	    if($row = $loadStmt->fetch())
	    {
	    	$object = new UserSetting();
	    	$object->LoadByID($row["id"]);
	    	$objects[] = $object;
	    }

	    return $objects;
	}

	public stastic function GetByUserIdAndSettingsname($_userid,$_settingsname)
	{
		$object = new UserSetting();
		$object->LoadByUserIdAndName($_userid,$_settingsname);
		return $object;
	}

	public static function LoadAll()
	{
		AuditHandler::FireEvent(__METHOD__,null);

		$loadQuery = "SELECT id FROM ".$this->tablename." ORDER BY ordernumber";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->execute();

		$objects = array();
	    while($row = $loadStmt->fetch())
	    {
	    	$object = new UserSetting();
	    	$object->LoadByID($row["id"]);
	    	$objects[] = $object;
	    }
	    return $objects;
	}

	public static function GetAll()
	{
		return self::LoadAll();
	}

	public static function GetById($_id)
	{
		$object = new UserSetting();
		$object ->LoadById($_id);
		return $object;
	}

}
