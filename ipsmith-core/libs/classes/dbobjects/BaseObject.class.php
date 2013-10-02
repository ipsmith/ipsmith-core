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

class BaseObject
{
	public $id = null;
	public $guid = null;
	public $createdat = null;
	public $createdby = null;
	public $modifiedat = null;
	public $modifiedby = null;

	public function IsValid()
	{
		return ($this->id > 0);
	}

	public function LoadMetaData($metaRow)
	{
		if(isset($metaRow["id"]))
		{
			$this->id = $metaRow["id"];
			if(isset($metaRow["guid"]) && $metaRow["guid"]!=null)
				$this->guid = $metaRow["guid"];

			if(isset($metaRow["createdat"]) && $metaRow["createdat"]!=null)
				$this->createdat = $metaRow["createdat"];

			if(isset($metaRow["createdby"]) && $metaRow["createdby"]!=null)
				$this->createdby = $metaRow["createdby"];

			if(isset($metaRow["modifiedat"]) && $metaRow["modifiedat"]!=null)
				$this->modifiedat = $metaRow["modifiedat"];

			if(isset($metaRow["modifiedby"]) && $metaRow["modifiedby"]!=null)
				$this->modifiedby = $metaRow["modifiedby"];
		}
	}

	public function SaveTableLog($_tablename, $_id, $_isupdate)
	{
		$tablelog = new TableLog();
		$tablelog->objecttablename = $_tablename;
		$tablelog->tableid = $_id;
		$tablelog->username = $_SESSION["userdata"]["username"];

	    if($_isupdate)
	    {
	    	$tablelog->action = 'ItemID #'.$_id.' updated';
	    }
	    else
	    {
	    	$tablelog->action = 'ItemID #'.$_id.' created';
	    }

	    $tablelog->Save();
	}
	public function SaveMetaData($_tablename, $_id, $_isupdate)
	{
			if($_tablename!='tablelog')
			{
				$this->SaveTableLog($_tablename,$_id,$_isupdate);
			}

			$metaDataQuery = "UPDATE ".$_tablename." ";
			if($_isupdate)
			{
				$metaDataQuery.="SET modifiedby=:currentuser, modifiedat=CURRENT_TIMESTAMP ";
			}
			else
			{
				$metaDataQuery.= "SET createdby=:currentuser ";
			}

			if($this->guid==null)
			{
				$metaDataQuery.=", guid=:guid ";
			}

			$metaDataQuery.= " WHERE id=:id";

			$metaDataStmt = Database::current()->prepare($metaDataQuery);
			if($this->guid==null || $this->guid=="")
			{
				$metaDataStmt->bindValue('guid',GuidGenerator::Create($_tablename));
			}
			$metaDataStmt->bindValue('currentuser',$_SESSION["userdata"]["username"]);
			$metaDataStmt->bindValue('id',$this->id);
          	$metaDataStmt->execute();
	}
}