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

class ExportTemplate extends BaseObject
{
	public $tablename = "export_templates";

	public $exportname = null;
	public $template_header = null;
	public $template_foreach = null;
	public $template_footer = null;
	public $force_assignment = null;
	public $select_local = null;
	public $select_duplicates_ip = null;
	public $select_duplicates_mac = null;
	public $select_duplicates_host = null;
	public $select_emptyip = null;
	public $select_emptymac = null;
	public $select_emptyhost = null;
	public $usecounter = false;
	public $dailycounter = 0;
	public $downloadextension = null;
	public $mimetype = null;
	public $lastexport = null;
	public $automaticupdates = true;

	public function Save()
	{
		$isupdate = $this->IsValid();
		AuditHandler::FireEvent(__METHOD__,array("id"=>$this->id));

		if($this->IsValid())
		{
			$saveQuery = "UPDATE ".$this->tablename." SET exportname=:exportname, template_header=:template_header, template_foreach=:template_foreach, template_footer=:template_footer, force_assignment=:force_assignment, select_local=:select_local, select_duplicates_ip=:select_duplicates_ip, select_duplicates_mac=:select_duplicates_mac, select_duplicates_host=:select_duplicates_host, select_emptyip=:select_emptyip, select_emptymac=:select_emptymac, select_emptyhost=:select_emptyhost, usecounter=:usecounter, dailycounter=:dailycounter, downloadextension=:downloadextension, mimetype=:mimetype, lastexport=:lastexport, automaticupdates=:automaticupdates WHERE id=:id;";
			$saveStmt = Database::current()->prepare($saveQuery);
			$saveStmt->bindValue('id',$this->id);
		}
		else
		{
			$saveQuery = "INSERT INTO ".$this->tablename." (exportname, template_header, template_foreach, template_footer, force_assignment, select_local, select_duplicates_ip, select_duplicates_mac, select_duplicates_host, select_emptyip, select_emptymac, select_emptyhost, usecounter, dailycounter, downloadextension, mimetype, lastexport, automaticupdates) VALUES (:exportname, :template_header, :template_foreach, :template_footer, :force_assignment, :select_local, :select_duplicates_ip, :select_duplicates_mac, :select_duplicates_host, :select_emptyip, :select_emptymac, :select_emptyhost, :usecounter, :dailycounter, :downloadextension, :mimetype, :lastexport, :automaticupdates);";
			$saveStmt = Database::current()->prepare($saveQuery);
		}

		$saveStmt->bindValue('exportname',$this->exportname);
		$saveStmt->bindValue('template_header',$this->template_header);
		$saveStmt->bindValue('template_foreach',$this->template_foreach);
		$saveStmt->bindValue('template_footer',$this->template_footer);
		$saveStmt->bindValue('force_assignment',$this->force_assignment);
		$saveStmt->bindValue('select_local',$this->select_local);
		$saveStmt->bindValue('select_duplicates_ip',$this->select_duplicates_ip);
		$saveStmt->bindValue('select_duplicates_mac',$this->select_duplicates_mac);
		$saveStmt->bindValue('select_duplicates_host',$this->select_duplicates_host);
		$saveStmt->bindValue('select_emptyip',$this->select_emptyip);
		$saveStmt->bindValue('select_emptymac',$this->select_emptymac);
		$saveStmt->bindValue('select_emptyhost',$this->select_emptyhost);
		$saveStmt->bindValue('usecounter',$this->usecounter);
		$saveStmt->bindValue('dailycounter',$this->dailycounter);
		$saveStmt->bindValue('downloadextension',$this->downloadextension);
		$saveStmt->bindValue('mimetype',$this->mimetype);
		$saveStmt->bindValue('lastexport',$this->lastexport);
		$saveStmt->bindValue('automaticupdates',$this->automaticupdates);

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
		$this->exportname = $row["exportname"];
		$this->template_header = $row["template_header"];
		$this->template_foreach = $row["template_foreach"];
		$this->template_footer = $row["template_footer"];
		$this->force_assignment = $row["force_assignment"];
		$this->select_local = $row["select_local"];
		$this->select_duplicates_ip = $row["select_duplicates_ip"];
		$this->select_duplicates_mac = $row["select_duplicates_mac"];
		$this->select_duplicates_host = $row["select_duplicates_host"];
		$this->select_emptyip = $row["select_emptyip"];
		$this->select_emptymac = $row["select_emptymac"];
		$this->select_emptyhost = $row["select_emptyhost"];
		$this->usecounter = $row["usecounter"];
		$this->dailycounter = $row["dailycounter"];
		$this->downloadextension = $row["downloadextension"];
		$this->mimetype = $row["mimetype"];
		$this->lastexport = $row["lastexport"];
		$this->automaticupdates = $row["automaticupdates"];
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

	public function LoadByExportName($_exportname)
	{
		AuditHandler::FireEvent(__METHOD__,array("param-exportname"=>$_exportname));

		$loadQuery = "SELECT * FROM ".$this->tablename." WHERE exportname=:exportname";
		$loadStmt = Database::current()->prepare($loadQuery);
          $loadStmt->bindValue('exportname',$_exportname);
          $loadStmt->execute();

	    if($row = $loadStmt->fetch())
	    {
	    		$this->LoadData($row);
	    }
	}

	public static function GetByExportName($_exportname)
	{
		$object = new ExportTemplate();
		$object->LoadByEntryId($_exportname);
		return $object;
	}

	public static function GetById($_id)
	{
		$object = new ExportTemplate();
		$object->LoadById($_id);
		return $object;
	}

	public static function GetAllForced()
	{
		AuditHandler::FireEvent(__METHOD__,array());

		$loadQuery = "SELECT id FROM export_templates WHERE force_assignment=1";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->execute();

		$objects = array();
	    while($row = $loadStmt->fetch())
	    {
			$object = new ExportTemplate();
			$object->LoadById($row["id"]);
			$objects[] = $object;
	    }

	    return $objects;
	}

	public static function GetAll()
	{
		AuditHandler::FireEvent(__METHOD__,array());

		$loadQuery = "SELECT * FROM export_templates";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->execute();

		$objects = array();
	    while($row = $loadStmt->fetch())
	    {
			$object = new ExportTemplate();
			$object->LoadById($row["id"]);
			$objects[] = $object;
	    }

	    return $objects;
	}

	public static function GetByEntryId($_entryid)
	{
		AuditHandler::FireEvent(__METHOD__,array('param-entryid'=>$_entryid));

		$loadQuery = "SELECT export_templates.* FROM export_templates LEFT JOIN entry2export on export_templates.id=entry2export.exportid WHERE entry2export.dataid=:entryid";
		$loadStmt = Database::current()->prepare($loadQuery);
		$loadStmt->bindValue('entryid',$_entryid);
		$loadStmt->execute();

		$objects = array();
	    while($row = $loadStmt->fetch())
	    {
			$object = new ExportTemplate();
			$object->LoadById($row["id"]);
			$objects[] = $object;
	    }

	    return $objects;
	}
}