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

class Ping extends BaseObject
{
	public $tablename = "pings";

	public $result 			= 0;
	public $entryid  	= null;
	public $description  	= null;
	public $labelclass  	= null;

	public function Save()
	{
		$isupdate = $this->IsValid();
		AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));

		if($this->IsValid())
		{
			$saveQuery = "UPDATE ".$this->tablename." SET result=:result, entryid=:etryid WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			$saveQuery = "INSERT INTO ".$this->tablename." (result, entryid) VALUES (:result, :entryid);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('result',$this->result);
		$saveStmt->bindValue('entryid',$this->entryid);
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
		$this->entryid= $row["entryid"];
		$this->result= $row["result"];

		switch($row["result"])
		{
			case "1":
				$this->labelclass ="label-success";
				$this->description = "Online";
			break;
			case "0":
				$this->labelclass ="label-important";
				$this->description = "Offline";
			break;
			case "-1":
				$this->labelclass ="label-warning";
				$this->description = "Fehler";
			break;
			default:
				$this->labelclass ="label-info";
				$this->description = "Ausstehend";
			break;
		}
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

	public function LoadByEntryId($_entryid)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-entryid"=>$_entryid));

		$loadQuery = "SELECT * FROM ".$this->tablename." WHERE entryid=:entryid";
		$loadStmt = Database::current()->prepare($loadQuery);
          $loadStmt->bindValue('entryid',$_entryid);
          $loadStmt->execute();

	    if($row = $loadStmt->fetch())
	    {
	    		$this->LoadData($row);
	    }
	    else
	    {
	    	$this->LoadData(array('entryid'=>$_entryid,'result'=>-2));
	    }

	}
	public static function GetByEntryId($_entryid)
	{
		$object = new Ping();
		$object->LoadByEntryId($_entryid);
		return $object;
	}


	public static function GetById($_id)
	{
		$object = new Ping();
		$object ->LoadById($_id);
		return $object;
	}

}
