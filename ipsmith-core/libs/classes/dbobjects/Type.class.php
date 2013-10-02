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

class Type extends BaseObject
{
	public $tablename = "types";

	public $typename = null;
	public $bgcolor_hex = null;
	public $bgcolor_r = 0;
	public $bgcolor_g = 0;
	public $bgcolor_b = 0;
	public $fgcolor_hex = null;
	public $fgcolor_r = 0;
	public $fgcolor_g = 0;
	public $fgcolor_b = 0;
	public $description = null;

	public function Save()
	{
		$isupdate = $this->IsValid();
		AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));

		if($this->IsValid())
		{
			$saveQuery = "UPDATE ".$this->tablename." SET typename=:typename, bgcolor_hex=:bgcolor_hex, bgcolor_r=:bgcolor_r, bgcolor_g=:bgcolor_g, bgcolor_b=:bgcolor_b, fgcolor_hex=:fgcolor_hex, fgcolor_r=:fgcolor_r, fgcolor_g=:fgcolor_g, fgcolor_b=:fgcolor_b, description=:description WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			$saveQuery = "INSERT INTO ".$this->tablename." (typename, bgcolor_hex, bgcolor_r, bgcolor_g, bgcolor_b, fgcolor_hex, fgcolor_r, fgcolor_g, fgcolor_b, description) VALUES (:typename, :bgcolor_hex, :bgcolor_r, :bgcolor_g, :bgcolor_b, :fgcolor_hex, :fgcolor_r, :fgcolor_g, :fgcolor_b, :description);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('typename',$this->typename);
		$saveStmt->bindValue('bgcolor_hex',$this->bgcolor_hex);
		$saveStmt->bindValue('bgcolor_r',$this->bgcolor_r);
		$saveStmt->bindValue('bgcolor_g',$this->bgcolor_g);
		$saveStmt->bindValue('bgcolor_b',$this->bgcolor_b);
		$saveStmt->bindValue('fgcolor_hex',$this->fgcolor_hex);
		$saveStmt->bindValue('fgcolor_r',$this->fgcolor_r);
		$saveStmt->bindValue('fgcolor_g',$this->fgcolor_g);
		$saveStmt->bindValue('fgcolor_b',$this->fgcolor_b);
		$saveStmt->bindValue('description',$this->description);

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
		$this->typename= $row["typename"];
		$this->bgcolor_hex= $row["bgcolor_hex"];
		$this->bgcolor_r= $row["bgcolor_r"];
		$this->bgcolor_g= $row["bgcolor_g"];
		$this->bgcolor_g= $row["bgcolor_g"];
		$this->fgcolor_hex= $row["fgcolor_hex"];
		$this->fgcolor_r= $row["fgcolor_r"];
		$this->fgcolor_g= $row["fgcolor_g"];
		$this->fgcolor_b= $row["fgcolor_b"];
		$this->description= $row["description"];
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

	public static function GetAll()
	{
		AuditHandler::FireEvent(__METHOD__,null);

		$loadQuery = "SELECT id FROM types";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->execute();

		$objects = array();
	    while($row = $loadStmt->fetch())
	    {
	    	$object = new Type();
	    	$object->LoadByID($row["id"]);
	    	$objects[] = $object;
	    }
	    return $objects;
	}

	public static function GetById($_id)
	{
		$object = new Type();
		$object ->LoadById($_id);
		return $object;
	}

}
