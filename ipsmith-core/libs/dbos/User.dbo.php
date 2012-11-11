<?php
/**
* User class, will be used for getting
* user related data from database.
*
* Extends @see BaseObject
*
* @author Rainer Bendig <rbe@ipsmith.org>
* @version 1.0
* @since 0.0.1
*/
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