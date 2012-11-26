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
 * or see http://www.ipsmith.org/docs/license
 *
 * For questions, help, comments, discussion, etc., please join the
 * IPSmith mailing list. Go to http://www.ipsmith.org/lists
 *
 **/

class User extends BaseObject
{
	public $id = null;
	public $username = null;
	public $password = null;
	public $email = null;
	public $firstname = null;
	public $lastname = null;
	public $apikey = null;
	public $createdat = null;
	public $createdby = null;
	public $modifiedat = null;
	public $modifiedby = null;

	public function Save()
	{

		$data = array(
			'username' => $this->username,
			'password' => $this->password,
			'email' => $this->email,
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'apikey' => $this->apikey,
			'createdat' => $this->createdat,
			'createdby' => $this->createdby,
			'modifiedat' => $this->modifiedat,
			'modifiedby' => $this->modifiedby
		);

		parent::Store('users',$data);
	}

	public function Delete()
	{
		parent::Remove('users');
	}


	public function GetByUsernameAndPassword($username,$password)
	{
		$password = userHashPassword($username,$password);
		$querydata = array( 'username' => $username, 'password' => $password );

	  $this->GetByValues($querydata);
	}

	public function Load($data)
	{
		$this->id = $data["id"];
		$this->username = $data["username"];
		$this->password = $data["password"];
		$this->email = $data["email"];
		$this->firstname = $data["firstname"];
		$this->lastname = $data["lastname"];
		$this->apikey = $data["apikey"];
		$this->createdat = $data["createdat"];
		$this->createdby = $data["createdby"];
		$this->modifiedat = $data["modifiedat"];
		$this->modifiedby = $data["modifiedby"];
	}

	public function GetByValues($querydata)
	{
		$data  = parent::GetBy('users' , $querydata);
		$this->Load($data);
	}

	public function GetById($id)
	{
		$data = parent::GetBy('users',array('id'=>$id));
		$this->Load($data);
	}
}