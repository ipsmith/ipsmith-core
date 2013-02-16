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

class Category extends BaseObject
{
	public $tablename = "categories";

	public $catname = null;
	public $ordernumber = 0;

	public function Save()
	{
		$isupdate = $this->IsValid();

		if($this->IsValid())
		{
			AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));
			$saveQuery = "UPDATE categories SET catname=:catname, ordernumber=:ordernumber WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));
			$saveQuery = "INSERT INTO categories (catname,ordernumber) VALUES (:catname,:ordernumber);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('catname',$this->catname);
		$saveStmt->bindValue('ordernumber',$this->ordernumber);
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
		$this->catname = $row["catname"];
		$this->ordernumber = $row["ordernumber"];
	}

	public function LoadAll()
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id));

		$loadQuery = "SELECT id FROM categories ORDER BY ordernumber";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->bindValue('id',$_id);
		$loadStmt->execute();

		$objects = array();
	    while($row = $loadStmt->fetch())
	    {
	    	$object = new Category();
	    	$object->LoadByID($row["id"]);
	    	$objects[] = $object;
	    }
	    return $objects;
	}

	public stastic function GetAll()
	{
		return self::LoadAll();
	}

	public function LoadById($_id)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-id"=>$_id));

		$loadQuery = "SELECT * FROM categories WHERE id=:id";
		$loadStmt = Database::current()->prepare($loadQuery);
        $loadStmt->bindValue('id',$_id);
        $loadStmt->execute();

	    if($row = $loadStmt->fetch())
	    {
    		$this->LoadData($row);
	    }
	}

	public static function GetById($_id)
	{
		$object = new Category();
		$object->LoadById($_id);
		return $object;
	}
}