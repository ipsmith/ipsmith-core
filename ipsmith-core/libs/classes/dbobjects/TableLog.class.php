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

class TableLog extends BaseObject
{
	public $tablename = "tablelog";

	public $objectablename = null;
	public $username = null;
	public $action = null;
	public $tableid = 0;

	public function Save()
	{
		$isupdate = $this->IsValid();

		if($this->IsValid())
		{
			AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));
			$saveQuery = "UPDATE tablelog SET objecttablename=:objecttablename, tableid=:tableid, username=:username, action=:action WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));
			$saveQuery = "INSERT INTO tablelog (username,action,objecttablename,tableid) VALUES (:username,:action,:objecttablename,:tableid);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('username',$this->username);
		$saveStmt->bindValue('action',$this->action);
		$saveStmt->bindValue('objecttablename',$this->objecttablename);
		$saveStmt->bindValue('tableid',$this->tableid);
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
		$this->username= $row["username"];
		$this->objecttablename= $row["objecttablename"];
		$this->tableid = $row["tableid"];
		$this->action= $row["action"];
	}

	public function LoadById($_id)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id));

		$loadQuery = "SELECT * FROM tablelog WHERE id=:id";
		$loadStmt = Database::current()->prepare($loadQuery);
          $loadStmt->bindValue('id',$_id);
          $loadStmt->execute();

	    if($row = $loadStmt->fetch())
	    {
	    		$this->LoadData($row);
	    }
	}

	public static function GetByTableNameAndId($_tablename,$_id)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id, "param-tablename"=>$_tablename));

		$loadQuery = "SELECT * FROM tablelog WHERE tableid=:tableid AND objecttablename=:objecttablename ORDER BY id DESC";
		$loadStmt = Database::current()->prepare($loadQuery);
          $loadStmt->bindValue('tableid',$_id);
          $loadStmt->bindValue('objecttablename',$_tablename);
          $loadStmt->execute();
          $objects = array();
	    while($row = $loadStmt->fetch())
	    {
	    		$object = new TableLog();
	    		$object->LoadById($row["id"]);
	    		$objects[] = $object;
	    }
	    return $objects;
	}


}