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

class BaseObject
{
     private $connection = null;

     function __construct()
     {
          global $doctrineConnection;
          $this->connection = $doctrineConnection;
     }

     function Escape($data)
     {

          return $data;
          $returndata = array();

          foreach ($data as $k => $v)
          {

               $returndata[$k] =  $this->connection->quote($v);
          }

          return $returndata;
     }

     function Store($table,$data)
     {

          $data = $this->Escape($data);

          if(isset($this->id) && $this->id>0)
          {
               $this->connection->update($table,$data, array('id' => $this->id )                                                                                                                                                                                               );
          }
          else
          {
               $this->connection->insert($table,$data);
          }
     }

     function Remove($table)
     {
          if(isset($this->id) && $this->id>0)
          {
               $this->connection->delete($table,array('id' => $this->id ));
          }
     }

     function GetBy($table,$querydata)
     {

          $q = 'SELECT * FROM '.$table.' WHERE ';
          $values = array();
          $i = 0;

          foreach ($querydata as $k => $v)
          {
               $values[] = $v;

               if($i>0)
               {
                    $q.=" AND ";
               }
               $q.= $k.' = ? ';

               $i=$i+1;
          }

          return $this->connection->fetchAssoc($q,$values);
     }


     public function GetAll($table, $querydata)
     {

          $q = 'SELECT * FROM '.$table.' WHERE ';
          $values = array();
          $i = 0;

          foreach ($querydata as $k => $v)
          {
               $values[] = $v;

               if($i>0)
               {
                    $q.=" AND ";
               }
               $q.= $k.' = ? ';

               $i=$i+1;
          }

          return $this->connection->fetchAll($q,$values);
     }
}