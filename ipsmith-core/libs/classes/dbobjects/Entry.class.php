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

class Entry extends BaseObject
{
	public $tablename = "entries";

	public $address = null;
	public $ip = null;
	public $ipnum = 0;
	public $mac = null;
	public $hostname = null;
	public $fqdn = null;
	public $addressrecord = "A";
	public $iptype = null;
	public $color_hex = null;
	public $color_r = 0;
	public $color_g = 0;
	public $color_b = 0;
	public $iconpath = null;
	public $locationid = 0;
	public $catid = 0;
	public $typeid = 0;

	public $ping = null;
	public $type = null;
	public $category = null;
	public $location = null;

	public function Save()
	{
		$isupdate = $this->IsValid();
		AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));
		$address = new IPAddress($this->ip);

		if($this->IsValid())
		{
			$saveQuery = "UPDATE ".$this->tablename." SET ip=:ip, ipnum=:ipnum, macaddress=:macaddress, hostname=:hostname, fqdn=:fqdn, addressrecord=:addressrecord, iptype=:iptype, color_hex=:color_hex, color_r=:color_r, color_g=:color_g, color_b=:color_b, iconpath=:iconpath, locationid=:locationid. catid=:catid, typeid=:typeid WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			$saveQuery = "INSERT INTO ".$this->tablename." (ip, ipnum, macaddress, hostname, fqdn, addressrecord, iptype, color_hex, color_r, color_g, color_b, iconpath, locationid, catid, typeid) VALUES (:ip, :ipnum, :macaddress, :hostname, :fqdn, :addressrecord, :iptype, :color_hex, :color_r, :color_g, :color_b, :iconpath, :locationid, :catid, :typeid);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('ip',$this->ip);
		$saveStmt->bindValue('ipnum',$address->DECIMAL);
		$saveStmt->bindValue('macaddress',$this->macaddress);
		$saveStmt->bindValue('hostname',$this->hostname);
		$saveStmt->bindValue('addressrecord',$address->AddressRecord);
		$saveStmt->bindValue('iptype',$address->IPType);
		$saveStmt->bindValue('color_hex',$this->color_hex);
		$saveStmt->bindValue('color_r',$this->color_r);
		$saveStmt->bindValue('color_g',$this->color_g);
		$saveStmt->bindValue('color_b',$this->color_b);
		$saveStmt->bindValue('iconpath',$this->iconpath);
		$saveStmt->bindValue('locationid',$this->locationid);
		$saveStmt->bindValue('catid',$this->catid);
		$saveStmt->bindValue('typeid',$this->typeid);
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
		$this->ip = $row["ip"];
		$this->ipnum = $row["ipnum"];
		$this->macaddress = $row["macaddress"];
		$this->hostname = $row["hostname"];
		$this->addressrecord = $row["addressrecord"];
		$this->iptype = $row["iptype"];
		$this->color_hex = $row["color_hex"];
		$this->color_r = $row["color_r"];
		$this->color_g = $row["color_g"];
		$this->color_b = $row["color_b"];
		$this->iconpath = $row["iconpath"];
		$this->locationid = $row["locationid"];
		$this->catid = $row["catid"];
		$this->typeid = $row["typeid"];

		$this->address = new IPAddress($row["ip"]);
		$this->category = Category::GetById($row["catid"]);
		$this->location = Location::GetById($row["locationid"]);
		$this->type = Type::GetById($row["typeid"]);
		$this->ping = Ping::GetByEntryId($row["id"]);
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

	public function RemoveFromExports()
	{
		AuditHandler::FireEvent(__METHOD__,array('param-id'=>$this->id));

		$deleteAssignmentQuery = 'DELETE FROM entry2export WHERE dataid=:dataid ;';
	    $deleteAssignmentStmt = Database::current()->prepare($deleteAssignmentQuery);
	    $deleteAssignmentStmt->bindValue('dataid',$this->id);
	    $deleteAssignmentStmt->execute();
	}

	public function AddToExport($export)
	{
		AuditHandler::FireEvent(__METHOD__,array('param-id'=>$this->id, 'param-exportid'=>$export));

		$exportQuery = "INSERT INTO entry2export (dataid,exportid) VALUES (:dataid, :exportid)";
		$exportStmt = Database::current()->prepare($exportQuery);
		$exportStmt->bindValue('dataid',$this->id);
		$exportStmt->bindValue('exportid',$export);
		$exportStmt->execute();
	}

	public function Delete()
	{
		AuditHandler::FireEvent(__METHOD__,array('param-id'=>$this->id));

		$this->RemoveFromExports();

		$deleteQuery = "DELETE FROM entries WHERE id=:id";
		$deleteStmt = Database::current()->prepare($deleteQuery);
		$deleteStmt->bindValue('id',$this->id);
		$deleteStmt->execute();
	}

	public static function LoadAll()
	{
		AuditHandler::FireEvent(__METHOD__,null);

		$loadQuery = "SELECT id FROM ".$this->tablename;
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->execute();

		$objects = array();
		while($row = $loadStmt->fetch())
		{
			$object = new Entry();
			$object->LoadByID($row["id"]);
			$objects[] = $object;
		}
		return $objects;
	}

	public static function GetByLocationAndCategory($_locationid, $_categoryid)
	{
		AuditHandler::FireEvent(__METHOD__,array('param-locationid'=>$_locationid, 'param-categoryid'=>$_categoryid));

		$loadQuery = "SELECT id FROM entries WHERE locationid=:locationid AND catid=:categoryid";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->bindValue('locationid',$_locationid);
		$loadStmt->bindValue('categoryid',$_categoryid);
		$loadStmt->execute();

		$objects = array();
		while($row = $loadStmt->fetch())
		{
			$object = new Entry();
			$object->LoadByID($row["id"]);
			$objects[] = $object;
		}
		return $objects;
	}

	public static function GetByLocationId($_locationid)
	{
		AuditHandler::FireEvent(__METHOD__,array('param-locationid'=>$_locationid));

		$loadQuery = "SELECT id FROM ".$this->tablename ." WHERE locationid=:locationid";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->bindValue('locationid',$_locationid);
		$loadStmt->execute();

		$objects = array();
		while($row = $loadStmt->fetch())
		{
			$object = new Entry();
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
		$object = new Entry();
		$object ->LoadById($_id);
		return $object;
	}

}
