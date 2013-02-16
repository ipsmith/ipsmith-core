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

class Location extends BaseObject
{
	public $tablename = "locations";

	public $locationname 	= null;
	public $ordernumber  	= 0;
	public $humanname    	= null;
	public $bemerkung    	= null;
	public $preselectedcat 	= 0;

	public function Save()
	{
		$isupdate = $this->IsValid();
		AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));

		if($this->IsValid())
		{
			$saveQuery = "UPDATE locations SET locationname=:locationname, ordernumber=:ordernumber, humanname=:humanname, bemerkung=:bemerkung, preselectedcat=:preselectedcat WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			$saveQuery = "INSERT INTO locations (locationname, ordernumber, humanname, bemerkung, preselectedcat) VALUES (:locationname, :ordernumber, :humanname, :bemerkung, :preselectedcat);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('locationname',$this->locationname);
		$saveStmt->bindValue('ordernumber',$this->ordernumber);
		$saveStmt->bindValue('humanname',$this->humanname);
		$saveStmt->bindValue('bemerkung',$this->bemerkung);
		$saveStmt->bindValue('preselectedcat',$this->preselectedcat);
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
		$this->locationname= $row["locationname"];
		$this->ordernumber= $row["ordernumber"];
		$this->humanname = $row["humanname"];
		$this->bemerkung= $row["bemerkung"];
		$this->preselectedcat= $row["preselectedcat"];
	}

	public function LoadById($_id)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id));

		$loadQuery = "SELECT * FROM locations WHERE id=:id";
		$loadStmt = Database::current()->prepare($loadQuery);
          $loadStmt->bindValue('id',$_id);
          $loadStmt->execute();

	    if($row = $loadStmt->fetch())
	    {
	    		$this->LoadData($row);
	    }
	}

	public function LoadAll()
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id));

		$loadQuery = "SELECT * FROM locations ORDER BY ordernumber";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->bindValue('id',$_id);
		$loadStmt->execute();

		$objects = array();
	    while($row = $loadStmt->fetch())
	    {
	    	$object = new Location();
	    	$object->LoadByID($row["id"]);
	    	$objects[] = $object;
	    }
	    return $objects;
	}

	public stastic function GetAll()
	{
		return self::LoadAll();
	}

	public static function GetById($_id)
	{
		$object = new Location();
		$object ->LoadById($_id);
		return $object;
	}

}
